<?php
declare(strict_types=1);

namespace Edde\Doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Edde\Doctrine\Exception\RepositoryException;
use Edde\Doctrine\Exception\RequiredResultException;
use Edde\Doctrine\Schema\PatchSchema;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Math\RandomServiceTrait;
use Edde\Query\Schema\QuerySchema;
use ReflectionClass;
use ReflectionException;

/**
 * @template         TEntity of object
 * @template         TFilter of object
 *
 * @template-extends IRepository<TEntity, TFilter>
 */
abstract class AbstractRepository implements IRepository {
	use EntityManagerTrait;
	use RandomServiceTrait;
	use SmartServiceTrait;

	/**
	 * @var ReflectionClass
	 */
	protected $reflectionClass;
	/**
	 * @var string
	 */
	protected $className;
	protected $orderBy = [];
	protected $fulltextOf = [];
	protected $searchOf = [];
	protected $matchOf = [];

	/**
	 * @param string $className
	 *
	 * @throws ReflectionException
	 */
	public function __construct(string $className) {
		$this->reflectionClass = new ReflectionClass($className);
		$this->className = $className;
	}

	/**
	 * @inheritDoc
	 */
	public function createEntity() {
		return $this->reflectionClass->newInstance();
	}

	/**
	 * @inheritDoc
	 */
	public function getRepository(): EntityRepository {
		return $this->entityManager->getRepository($this->className);
	}

	/**
	 * @inheritDoc
	 */
	public function save(SmartDto $dto) {
		$this->entityManager->persist(
			$entity = $dto->instanceOf($this->className, true)
		);
		return $entity;
	}

	/**
	 * @inheritDoc
	 */
	public function upsert(SmartDto $dto) {
		try {
			/**
			 * Patch contains entity resolution, so if it fails,
			 * we can try create a new entity.
			 */
			return $this->patch(
				$this->smartService->from([
					'patch'  => $dto->getSmartDto('update'),
					'filter' => $dto->getSmartDto('filter'),
				], PatchSchema::class)
			);
		} catch (RepositoryException $exception) {
			return $this->save($dto->getSmartDto('create', true));
		}
	}

	/**
	 * @inheritDoc
	 */
	public function patch(SmartDto $dto) {
		$dto->ensure([
			'filter',
			'patch',
		]);
		$this->entityManager->persist(
			$entity = $dto
				->getSmartDto('patch', true)
				->exportTo(
					$this->resolveEntityOrThrow($dto),
					true
				)
		);
		return $entity;
	}

	/**
	 * @inheritDoc
	 */
	public function select(string $alias): QueryBuilder {
		return $this->getRepository()
			->createQueryBuilder($alias);
	}

	/**
	 * @inheritDoc
	 */
	public function find(string $id, string $message = null) {
		if (!($entity = $this->getRepository()->find($id))) {
			throw new RequiredResultException($message ?? sprintf('Cannot find [%s] by [%s]!', $this->className, $id), 500);
		}
		return $this->hydrate($entity);
	}

	public function findBy(SmartDto $query) {
		try {
			return $this->findByOrThrow($query);
		} catch (NoResultException $exception) {
			return null;
		}
	}

	public function findByOrThrow(SmartDto $query) {
		return $this->hydrate(
			$this->toQuery('q', $query)->getQuery()->getSingleResult()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function all(string $alias): array {
		return $this->toHydrate(
			$this->select($alias)
				->getQuery()
				->getResult()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function total(SmartDto $query): int {
		/**
		 * Here we have to create empty query builder and setup it manually as the one from Repository
		 * creates default SELECT / FROM parts in the query.
		 */
		$queryBuilder = $this->entityManager->createQueryBuilder();
		$queryBuilder
			->select("COUNT(c)")
			->from($this->className, "c");
		$query->knownWithValue('filter') && $this->applyWhere("c", $query->getSmartDto('filter'), $query, $queryBuilder);
		return (int)$queryBuilder->getQuery()->getSingleScalarResult();
	}

	/**
	 * @inheritDoc
	 */
	public function toQuery(string $alias, SmartDto $query): QueryBuilder {
		$this->smartService->check($query, QuerySchema::class);
		$cursor = $query->getSmartDto('cursor', true);
		$queryBuilder = $this->select($alias)
			->setFirstResult($cursor->getSafeValue('page'))
			->setMaxResults($cursor->getSafeValue('size'));
		$this->applyQuery($alias, $query, $queryBuilder);
		return $queryBuilder;
	}

	/**
	 * @inheritDoc
	 */
	public function query(string $alias, SmartDto $query): array {
		return $this->toHydrate(
			$this->toQuery($alias, $query)
				->getQuery()
				->getResult()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteBy(SmartDto $query) {
		$entity = $this->find($query->getValue('id'));
		$this->entityManager->remove($entity);
		return $entity;
	}

	public function deleteWith(SmartDto $query): void {
		$this->applyWhere(
			'd',
			$query->getSmartDto('filter', true),
			$query,
			$queryBuilder = $this->getRepository()
				->createQueryBuilder('d')
		);
		$queryBuilder->delete()->getQuery()->execute();
	}

	/**
	 * @inheritDoc
	 */
	public function resolveEntity(SmartDto $dto) {
		try {
			if ($id = $dto->getSmartDto('filter', true)->getSafeValue('id')) {
				return $this->find($id);
			}
			return null;
		} catch (RequiredResultException $exception) {
			return null;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function resolveEntityOrThrow(SmartDto $dto) {
		if (!($entity = $this->resolveEntity($dto))) {
			throw new RequiredResultException(sprintf('Cannot resolve entity from DTO [%s].', $dto->getName()));
		}
		return $entity;
	}

	/**
	 * Overall QueryBuilder mutator for this repository
	 *
	 * @param string       $alias
	 * @param SmartDto     $query
	 * @param QueryBuilder $queryBuilder
	 *
	 * @return void
	 * @throws SmartDtoException
	 */
	protected function applyQuery(string $alias, SmartDto $query, QueryBuilder $queryBuilder): void {
		$this->applyWhere($alias, $query->getSmartDto('filter', true), $query, $queryBuilder);
		$this->applyOrderBy($alias, $query->getSmartDto('orderBy', true), $query, $queryBuilder);
	}

	/**
	 * @param string       $alias
	 * @param SmartDto     $filter
	 * @param SmartDto     $query
	 * @param QueryBuilder $queryBuilder
	 *
	 * @return void
	 * @throws SmartDtoException
	 */
	protected function applyWhere(string $alias, SmartDto $filter, SmartDto $query, QueryBuilder $queryBuilder): void {
		foreach ($this->fulltextOf as $field => $value) {
			if ($filter->knownWithValue($field)) {
				$this->fulltextOf($queryBuilder, $alias, $field, $filter->getValue($field));
			}
		}
		$filter->knownWithValue('fulltext') && !empty($this->searchOf) && $this->searchOf($queryBuilder, $alias, $filter->getValue('fulltext'), $this->searchOf);
		foreach ($this->matchOf as $field => $value) {
			$filter->knownWithValue($field) && $this->matchOf($queryBuilder, $alias, $field, $filter->getValue($field));
		}
	}

	protected function applyOrderBy(string $alias, SmartDto $orderBy, SmartDto $query, QueryBuilder $queryBuilder): void {
		foreach ($this->orderBy as $name => $order) {
			if (is_string($order) && !in_array($order = strtoupper($order), [
					'ASC',
					'DESC',
				])) {
				$order = 'ASC';
			} else if (is_bool($order)) {
				$order = $order ? 'ASC' : 'DESC';
			} else if (!is_string($order)) {
				$order = 'ASC';
			}
			$queryBuilder->addOrderBy($this->field($name, $alias), $order);
		}
	}

	protected function toHydrate(array $result): array {
		return array_map([
			$this,
			'hydrate',
		], $result);
	}

	protected function hydrate($item) {
		return $item;
	}

	protected function paramOf(QueryBuilder $queryBuilder, $value): string {
		$param = $this->randomService->chars(16);
		$queryBuilder->setParameter($param, $value);
		return $param;
	}

	/**
	 * Helper function to generate "fulltext" where condition using LIKE
	 *
	 * @param QueryBuilder $queryBuilder
	 * @param string       $alias
	 * @param string       $field
	 * @param string       $value
	 *
	 * @return void
	 */
	protected function fulltextOf(QueryBuilder $queryBuilder, string $alias, string $field, string $value) {
		$queryBuilder->andWhere($this->field($field, $alias) . " LIKE :" . $this->paramOf($queryBuilder, "%$value%"));
	}

	/**
	 * Helper method to generate exact match where condition
	 *
	 * @param QueryBuilder $queryBuilder
	 * @param string       $alias
	 * @param string       $field
	 * @param mixed        $value
	 *
	 * @return void
	 */
	protected function matchOf(QueryBuilder $queryBuilder, string $alias, string $field, $value) {
		$queryBuilder->andWhere($this->field($field, $alias) . " = :" . $this->paramOf($queryBuilder, $value));
	}

	protected function searchOf(QueryBuilder $queryBuilder, string $alias, string $value, array $fields) {
		$queryBuilder->andWhere($queryBuilder->expr()->orX(...array_map(function (string $field) use ($queryBuilder, $value, $alias) {
			return $queryBuilder->expr()->like($this->field($field, $alias), ':' . $this->paramOf($queryBuilder, "%$value%"));
		}, $fields)));
	}

	/**
	 * Translate the given field with the given alias
	 *
	 * @param string $field
	 * @param string $alias
	 *
	 * @return string
	 */
	protected function field(string $field, string $alias): string {
		return str_replace('$', $alias, $field);
	}
}
