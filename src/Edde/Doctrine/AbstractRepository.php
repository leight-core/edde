<?php
declare(strict_types=1);

namespace Edde\Doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Edde\Doctrine\Exception\RepositoryException;
use Edde\Doctrine\Exception\RequiredResultException;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Math\RandomServiceTrait;
use Edde\Query\Dto\Query;
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

	public function createEntity() {
		return $this->reflectionClass->newInstance();
	}

	public function getRepository(): EntityRepository {
		return $this->entityManager->getRepository($this->className);
	}

	public function select(string $alias): QueryBuilder {
		return $this->getRepository()
			->createQueryBuilder($alias);
	}

	public function find(string $id, string $message = null) {
		if (!($entity = $this->getRepository()->find($id))) {
			throw new RequiredResultException($message || sprintf('Cannot find [%s] by [%s]!', $this->className, $id), 500);
		}
		return $this->hydrate($entity);
	}

	public function all(string $alias): array {
		return $this->toHydrate($this->select($alias)
			->getQuery()
			->getResult());
	}

	public function total(Query $query): int {
		/**
		 * Here we have to create empty query builder and setup it manually as the one from Repository
		 * creates default SELECT / FROM parts in the query.
		 */
		$queryBuilder = $this->entityManager->createQueryBuilder();
		$queryBuilder
			->select("COUNT(c)")
			->from($this->className, "c");
		$this->applyWhere("c", $query->filter, $queryBuilder);
		return (int)$queryBuilder->getQuery()->getSingleScalarResult();
	}

	public function toQuery(string $alias, Query $query): QueryBuilder {
		$queryBuilder = $this->select($alias)
			->setFirstResult($query->page)
			->setMaxResults($query->size);
		$this->applyQuery($alias, $query->filter, $queryBuilder);
		foreach ($this->orderBy as $name => $order) {
			if (is_string($order) && !in_array($order = strtoupper($order), [
					'ASC',
					'DESC',
				])) {
				$order = 'ASC';
			} else if (!is_bool($order)) {
				$order = 'ASC';
			}
			$queryBuilder->addOrderBy($this->field($name, $alias), $order);
		}
		return $queryBuilder;
	}

	public function withQueryDto(string $alias, SmartDto $query): QueryBuilder {
		$this->smartService->check($query, QuerySchema::class);
		$cursor = $query->getSmartDto('cursor');
		$filter = $query->getSmartDto('filter');
		$orderBy = $query->getSmartDto('orderBy');
		return $this->toQuery($alias, new Query(
			$filter ? $filter->export() : null,
			$orderBy ? $orderBy->export() : null,
			$cursor ? $cursor->getSafeValue('page', null) : null,
			$cursor ? $cursor->getSafeValue('size', null) : null
		));
	}

	public function withQuery(string $alias, SmartDto $query): array {
		return $this->toHydrate(
			$this->withQueryDto($alias, $query)
				->getQuery()
				->getResult()
		);
	}

	public function query(string $alias, Query $query): array {
		return $this->toHydrate(
			$this->toQuery($alias, $query)
				->getQuery()
				->getResult()
		);
	}

	public function save(SmartDto $dto) {
		$this->entityManager->persist(
			$entity = $dto->instanceOf($this->className)
		);
		return $entity;
	}

	public function patch(SmartDto $dto) {
		if (!$dto->known('id')) {
			throw new RepositoryException(sprintf('Smart DTO [%s] does not have ID attribute in the schema.', $dto->getName()));
		} else if ($dto->isUndefined('id')) {
			throw new RepositoryException(sprintf('Smart DTO [%s::id] is undefined.', $dto->getName()));
		}
		$this->entityManager->persist(
			$entity = $dto->exportTo(
				$this->find($dto->getValueOrThrow('id'))
			)
		);
		return $entity;
	}

	public function applyQuery(string $alias, ?object $filter, QueryBuilder $queryBuilder): void {
		$this->applyWhere($alias, $filter, $queryBuilder);
	}

	public function applyWhere(string $alias, ?object $filter, QueryBuilder $queryBuilder): void {
		foreach ($this->fulltextOf as $field => $value) {
			isset($filter->$value) && $this->fulltextOf($queryBuilder, $alias, $field, $filter->$value);
		}
		isset($filter->fulltext) && !empty($this->searchOf) && $this->searchOf($queryBuilder, $alias, $filter->fulltext, $this->searchOf);
		foreach ($this->matchOf as $field => $value) {
			isset($filter->$value) && $this->matchOf($queryBuilder, $alias, $field, $filter->$value);
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

	protected function fulltextOf(QueryBuilder $queryBuilder, string $alias, string $field, string $value) {
		$queryBuilder->where($this->field($field, $alias) . " LIKE :" . $this->paramOf($queryBuilder, "%$value%"));
	}

	protected function matchOf(QueryBuilder $queryBuilder, string $alias, string $field, string $value) {
		$queryBuilder->where($this->field($field, $alias) . " = :" . $this->paramOf($queryBuilder, $value));
	}

	protected function searchOf(QueryBuilder $queryBuilder, string $alias, string $value, $fields) {
		$queryBuilder->where($queryBuilder->expr()->orX(...array_map(function (string $field) use ($queryBuilder, $value, $alias) {
			return $queryBuilder->expr()->like($this->field($field, $alias), ':' . $this->paramOf($queryBuilder, "%$value%"));
		}, $fields)));
	}

	protected function field(string $field, string $alias): string {
		return str_replace('$', $alias, $field);
	}
}
