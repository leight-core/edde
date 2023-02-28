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

	public function __construct(string $className) {
		$this->entityRepository = $this->entityManager->getRepository($className);
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
		return $this->getQueryBuilder($alias)
			->setFirstResult($query->page)
			->setMaxResults($query->size)
			->getQuery()
			->getResult();
	}

	/**
	 * @param TEntity $entity
	 */
	public function save($entity) {
		$this->entityManager->persist($entity);
		return $this;
	}
}
