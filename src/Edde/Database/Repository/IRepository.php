<?php
declare(strict_types=1);

namespace Edde\Database\Repository;

use Cake\Database\Query;
use Edde\Database\Exception\RepositoryException;
use Edde\Database\Exception\RequiredResultException;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;

/**
 * @template TEntity of object
 * @template TFilter of object
 */
interface IRepository {
	/**
	 * Insert a new entity into database
	 *
	 * @param SmartDto $dto
	 * @param bool     $raw
	 *
	 * @return TEntity
	 *
	 * @throws SmartDtoException
	 */
	public function create(SmartDto $dto, bool $raw = true);

	/**
	 * Smart create/update.
	 *
	 * @param SmartDto $dto
	 *
	 * @return TEntity
	 * @throws SmartDtoException
	 */
	public function upsert(SmartDto $dto);

	/**
	 * Updates an existing entity, requires ID property present.
	 *
	 * @param SmartDto $dto
	 *
	 * @return TEntity
	 *
	 * @throws RepositoryException
	 * @throws RequiredResultException
	 * @throws SmartDtoException
	 */
	public function update(SmartDto $dto);

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
	 * @throws SmartDtoException
	 * @throws RequiredResultException
	 */
	public function findByOrThrow(SmartDto $query);

	/**
	 * @param SmartDto $query
	 *
	 * @return int
	 *
	 * @throws SmartDtoException
	 */
	public function total(SmartDto $query): int;

	/**
	 * Returns pure shiny new Query for this repo.
	 *
	 * @return Query
	 */
	public function queryOf(): Query;

	/**
	 * Return select to this repository
	 *
	 * @param array $fields
	 * @param bool  $override
	 *
	 * @return Query
	 */
	public function select(array $fields = [], bool $override = false): Query;

	/**
	 * @param Query $query
	 *
	 * @return object
	 */
	public function fetch(Query $query): object;

	/**
	 * @param Query $query
	 *
	 * @return object[]
	 */
	public function list(Query $query): array;

	/**
	 * Query using SmartDto
	 *
	 * @throws SmartDtoException
	 */
	public function toQuery(SmartDto $query): Query;

	/**
	 * @param string   $alias
	 * @param SmartDto $query
	 *
	 * @return object[]
	 * @throws SmartDtoException
	 */
	public function query(string $alias, SmartDto $query): array;

	/**
	 * @param SmartDto $query
	 *
	 * @return TEntity
	 * @throws \Edde\Database\Exception\RequiredResultException
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
	 * @throws \Edde\Database\Exception\RequiredResultException
	 */
	public function resolveEntityOrThrow(SmartDto $dto);
}
