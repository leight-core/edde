<?php
declare(strict_types=1);

namespace Edde\Doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Edde\Query\Dto\Query;

/**
 * @template TEntity
 */
abstract class AbstractRepository {
	use EntityManagerTrait;

	/**
	 * @var EntityRepository
	 */
	protected $entityRepository;
	protected $orderBy;

	public function __construct(string $className, array $orderBy = []) {
		$this->entityRepository = $this->entityManager->getRepository($className);
		$this->orderBy = [];
	}

	public function getQueryBuilder(string $alias): QueryBuilder {
		return $this->entityRepository
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
}
