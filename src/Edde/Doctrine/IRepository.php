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
	 * Saves a new entity.
	 *
	 * @param SmartDto $dto
	 *
	 * @return TEntity
	 *
	 * @throws ReflectionException
	 * @throws SmartDtoException
	 */
	public function save(SmartDto $dto, bool $raw = true);

	/**
	 * Smart create/update.
	 *
	 * @param SmartDto $dto
	 *
	 * @return TEntity
	 * @throws SmartDtoException
	 * @throws ReflectionException
	 */
	public function upsert(SmartDto $dto);

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
	 * @param SmartDto $query
	 *
	 * @return TEntity
	 */
	public function findBy(SmartDto $query);

	/**
	 * @param SmartDto $query
	 *
	 * @return TEntity
	 * @throws NoResultException
	 */
	public function findByOrThrow(SmartDto $query);

	/**
	 * @return TEntity[]
	 */
	public function all(string $alias): array;

	/**
	 * @param SmartDto $query
	 *
	 * @return int
	 *
	 * @throws NoResultException
	 * @throws NonUniqueResultException
	 * @throws SmartDtoException
	 */
	public function total(SmartDto $query): int;

	/**
	 * Query using SmartDto
	 *
	 * @throws SmartDtoException
	 */
	public function toQuery(string $alias, SmartDto $query): QueryBuilder;

	/**
	 * @param string   $alias
	 * @param SmartDto $query
	 *
	 * @return TEntity[]
	 * @throws SmartDtoException
	 */
	public function query(string $alias, SmartDto $query): array;

	/**
	 * @param SmartDto $query
	 *
	 * @return TEntity
	 * @throws RequiredResultException
	 * @throws SmartDtoException
	 */
	public function deleteBy(SmartDto $query);

	public function deleteWith(SmartDto $query): void;

	/**
	 * Original use case is to resolve an entity for "upsert" (called before any action) to ensure
	 * consistent result for both create/update.
	 *
	 * If create fails (primarily for unique constraint), you have to be sure the right Entity is patched,
	 * so this method resolves the entity *before* create (so if found, patch is used instead).
	 *
	 * By default it uses "filter.id" to run patch.
	 *
	 * @param SmartDto $dto
	 *
	 * @return TEntity
	 */
	public function resolveEntity(SmartDto $dto);

	/**
	 * @param SmartDto $dto
	 *
	 * @return TEntity
	 * @throws RequiredResultException
	 */
	public function resolveEntityOrThrow(SmartDto $dto);
}
