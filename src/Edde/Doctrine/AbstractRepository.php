<?php
declare(strict_types=1);

namespace Edde\Doctrine;

use Doctrine\ORM\QueryBuilder;
use Edde\Math\RandomServiceTrait;
use Edde\Query\Dto\Query;

/**
 * @template TEntity
 */
abstract class AbstractRepository {
	use EntityManagerTrait;
	use RandomServiceTrait;

	/**
	 * @var string
	 */
	protected $className;
	protected $orderBy;

	public function __construct(string $className, array $orderBy = []) {
		$this->className = $className;
		$this->orderBy = $orderBy;
	}

	public function getQueryBuilder(string $alias): QueryBuilder {
		return $this->entityManager->getRepository($this->className)
			->createQueryBuilder($alias);
	}

	/**
	 * @return TEntity[]
	 */
	public function all(string $alias) {
		return $this->getQueryBuilder($alias)
			->getQuery()
			->getResult();
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
		return $this->toQuery($alias, $query)
			->getQuery()
			->getResult();
	}

	public function toQuery(string $alias, Query $query): QueryBuilder {
		$queryBuilder = $this->getQueryBuilder($alias)
			->setFirstResult($query->page)
			->setMaxResults($query->size);
		$this->applyWhere($alias, $query->filter, $queryBuilder);
		foreach ($this->orderBy as $name => $order) {
			$queryBuilder->addOrderBy("$alias.$name", $order ? "ASC" : "DESC");
		}
		return $queryBuilder;
	}

	public function applyWhere(string $alias, $filter, QueryBuilder $queryBuilder) {
	}

	/**
	 * @param TEntity $entity
	 */
	public function save($entity) {
		$this->entityManager->persist($entity);
		return $this;
	}

	protected function fulltextOf(QueryBuilder $queryBuilder, string $field, string $value) {
		$param = $this->randomService->chars(16);
		$queryBuilder->where("$field LIKE :$param");
		$queryBuilder->setParameter($param, "%$value%");
	}
}
