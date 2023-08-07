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
	public function select(string $alias): QueryBuilder;

	/**
	 * @param string      $id
	 * @param string|null $message
	 *
	 * @return TEntity
	 *
	 * @throws RequiredResultException
	 */
	public function find(string $id, string $message = null);

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
	 * Query using SmartDto
	 */
	public function withQueryDto(string $alias, SmartDto $query): QueryBuilder;

	public function withQuery(string $alias, SmartDto $query): array;

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

	/**
	 * This method enables heavy query modifications; it's not intended to use with adding simple filter and so on. It's more like
	 * adding joins, another selects and so on.
	 *
	 * @param string       $alias
	 * @param object|null  $filter
	 * @param QueryBuilder $queryBuilder
	 *
	 * @return void
	 */
	public function applyQuery(string $alias, ?object $filter, QueryBuilder $queryBuilder): void;

	/**
	 * Here you should really only apply filters; you could eventually append some data, if they're related to the filter only, but
	 * main idea is to just put "simple" `where foo = bar`.
	 *
	 * @param string       $alias
	 * @param object|null  $filter
	 * @param QueryBuilder $queryBuilder
	 *
	 * @return void
	 */
	public function applyWhere(string $alias, ?object $filter, QueryBuilder $queryBuilder): void;
}
