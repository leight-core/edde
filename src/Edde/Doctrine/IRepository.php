<?php
declare(strict_types=1);

namespace Edde\Doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Edde\Doctrine\Exception\RepositoryException;
use Edde\Doctrine\Exception\RequiredResultException;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Query\Dto\Query;
use ReflectionException;

/**
 * @template TEntity of object
 * @template TFilter of object
 */
interface IRepository {
	/**
	 * @return TEntity
	 *
	 * @throws ReflectionException
	 */
	public function createEntity();

	/**
	 * @return EntityRepository
	 */
	public function getRepository(): EntityRepository;

	/**
	 * @param string $alias
	 *
	 * @return QueryBuilder
	 */
	public function getQueryBuilder(string $alias): QueryBuilder;

	/**
	 * @param string $id
	 *
	 * @return TEntity
	 *
	 * @throws RequiredResultException
	 */
	public function find(string $id);

	/**
	 * @return TEntity[]
	 */
	public function all(string $alias): array;

	/**
	 * @param string $alias
	 * @param Query  $query
	 *
	 * @return QueryBuilder
	 */
	public function toQuery(string $alias, Query $query): QueryBuilder;

	/**
	 * @param Query $query
	 *
	 * @return int
	 *
	 * @throws NoResultException
	 * @throws NonUniqueResultException
	 */
	public function total(Query $query): int;

	/**
	 * @param string $alias
	 * @param Query  $query
	 *
	 * @return TEntity
	 */
	public function query(string $alias, Query $query): array;

	/**
	 * Saves a new entity.
	 *
	 * @param SmartDto $dto
	 *
	 * @return TEntity
	 *
	 * @throws ReflectionException
	 * @throws SmartDtoException
	 */
	public function save(SmartDto $dto);

	/**
	 * Updates an existing entity, requires ID property present.
	 *
	 * @param SmartDto $dto
	 *
	 * @return TEntity
	 *
	 * @throws ReflectionException
	 * @throws RepositoryException
	 * @throws RequiredResultException
	 * @throws SmartDtoException
	 */
	public function patch(SmartDto $dto);
}
