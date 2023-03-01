<?php
declare(strict_types=1);

namespace Edde\Doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Edde\Doctrine\Exception\RepositoryException;
use Edde\Math\RandomServiceTrait;
use Edde\Query\Dto\Query;

/**
 * @template TEntity of object
 * @template TFilter of object
 */
abstract class AbstractRepository {
	use EntityManagerTrait;
	use RandomServiceTrait;

	/**
	 * @var string
	 */
	protected $className;
	protected $orderBy = [];
	protected $fulltextOf = [];
	protected $searchOf = [];

	public function __construct(string $className) {
		$this->className = $className;
	}

	public function getRepository(): EntityRepository {
		return $this->entityManager->getRepository($this->className);
	}

	public function getQueryBuilder(string $alias): QueryBuilder {
		return $this->getRepository()
			->createQueryBuilder($alias);
	}

	/**
	 * @param string $id
	 *
	 * @return object
	 * @psal-return TEntity
	 *
	 * @throws RepositoryException
	 */
	public function find(string $id) {
		if (!($entity = $this->getRepository()->find($id))) {
			throw new RepositoryException(sprintf('Cannot find [%s] by [%s]!', $this->className, $id), 500);
		}
		return $entity;
	}

	/**
	 * @return object[]
	 * @psalm-return TEntity[]
	 */
	public function all(string $alias) {
		return $this->toHydrate($this->getQueryBuilder($alias)
			->getQuery()
			->getResult());
	}

	public function total(Query $query): int {
		$queryBuilder = $this->entityManager->createQueryBuilder();
		$queryBuilder
			->select("COUNT(c)")
			->from($this->className, "c");
		$this->alterQuery("c", $query->filter, $queryBuilder);
		return (int)$queryBuilder->getQuery()->getSingleScalarResult();
	}

	/**
	 * @param string $alias
	 * @param Query  $query
	 *
	 * @return TEntity
	 */
	public function query(string $alias, Query $query) {
		return $this->toHydrate(
			$this->toQuery($alias, $query)
				->getQuery()
				->getResult()
		);
	}

	public function toQuery(string $alias, Query $query): QueryBuilder {
		$queryBuilder = $this->getQueryBuilder($alias)
			->setFirstResult($query->page)
			->setMaxResults($query->size);
		$this->alterQuery($alias, $query->filter, $queryBuilder);
		foreach ($this->orderBy as $name => $order) {
			$queryBuilder->addOrderBy("$alias.$name", $order ? "ASC" : "DESC");
		}
		return $queryBuilder;
	}

	/**
	 * @param string        $alias
	 * @param object|null   $filter
	 * @param QueryBuilder  $queryBuilder
	 *
	 * @psalm-param TFilter $filter
	 *
	 * @return void
	 */
	public function alterQuery(string $alias, ?object $filter, QueryBuilder $queryBuilder) {
		foreach ($this->fulltextOf as $field => $value) {
			isset($filter->$value) && $this->fulltextOf($queryBuilder, "$alias.$field", $filter->$value);
		}
		isset($filter->fulltext) && !empty($this->searchOf) && $this->searchOf($queryBuilder, $filter->fulltext, $this->searchOf);
	}

	/**
	 * @param TEntity $entity
	 */
	public function save($entity) {
		$this->entityManager->persist($entity);
		return $this;
	}

	protected function toHydrate(array $result) {
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
		$queryBuilder->setParameter($param, "%$value%");
		return $param;
	}

	protected function fulltextOf(QueryBuilder $queryBuilder, string $field, string $value) {
		$queryBuilder->where("$field LIKE :" . $this->paramOf($queryBuilder, $value));
	}

	protected function matchOf(QueryBuilder $queryBuilder, string $field, string $value) {
		$queryBuilder->where("$field = :" . $this->paramOf($queryBuilder, $value));
	}

	protected function searchOf(QueryBuilder $queryBuilder, string $value, $fields) {
		$queryBuilder->where($queryBuilder->expr()->orX(array_map(function (string $field) use ($queryBuilder, $value) {
			return $queryBuilder->expr()->like($field, ':' . $this->paramOf($queryBuilder, $value));
		}, $fields)));
	}
}