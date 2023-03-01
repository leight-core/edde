<?php
declare(strict_types=1);

namespace Edde\Doctrine;

use Doctrine\ORM\QueryBuilder;
use Edde\Math\RandomServiceTrait;
use Edde\Query\Dto\Query;

/**
 * @template TEntity
 * @template TFilter
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

	public function __construct(string $className) {
		$this->className = $className;
	}

	public function getQueryBuilder(string $alias): QueryBuilder {
		return $this->entityManager->getRepository($this->className)
			->createQueryBuilder($alias);
	}

	/**
	 * @return TEntity[]
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
		$this->applyWhere("c", $query->filter, $queryBuilder);
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
	 * @param string       $alias
	 * @param TFilter      $filter
	 * @param QueryBuilder $queryBuilder
	 *
	 * @return void
	 */
	public function alterQuery(string $alias, $filter, QueryBuilder $queryBuilder) {
		foreach ($this->fulltextOf as $field => $value) {
			isset($filter->$value) && $this->fulltextOf($queryBuilder, "$alias.$field", $filter->$value);
		}
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

	protected function paramOf(QueryBuilder $queryBuilder, $value) {
		$param = $this->randomService->chars(16);
		$queryBuilder->setParameter($param, "%$value%");
		return $param;
	}

	protected function fulltextOf(QueryBuilder $queryBuilder, string $field, string $value) {
		$queryBuilder->where("$field LIKE :" . $this->paramOf($queryBuilder, $value));
	}

	protected function searchOf(QueryBuilder $queryBuilder, string $value, $fields) {
		$queryBuilder->where($queryBuilder->expr()->orX(array_map(function (string $field) use ($queryBuilder, $value) {
			return $queryBuilder->expr()->like($field, ':' . $this->paramOf($queryBuilder, $value));
		}, $fields)));
	}
}
