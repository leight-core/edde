<?php
declare(strict_types=1);

namespace Edde\Database\Repository;

use Cake\Database\Query;
use Edde\Database\Exception\RepositoryException;
use Edde\Database\Exception\RequiredResultException;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Mapper\IMapper;
use Edde\Schema\ISchema;

/**
 * @template TEntity of object
 * @template TFilter of object
 */
interface IRepository extends IMapper {
    public function getSchema(): ISchema;

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
    public function create(SmartDto $dto, bool $raw = false): SmartDto;

    /**
     * Smart create/update.
     *
     * @param SmartDto $dto
     *
     * @return TEntity
     * @throws SmartDtoException
     */
    public function upsert(SmartDto $dto, bool $raw = false): SmartDto;

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
    public function update(SmartDto $dto, bool $raw = false): SmartDto;

    /**
     * @param string      $id
     * @param string|null $message
     *
     * @return TEntity
     *
     * @throws RequiredResultException
     */
    public function find(string $id, string $message = null): SmartDto;

    public function findSafe(?string $id, string $message = null): ?SmartDto;

    /**
     * @param SmartDto $query
     *
     * @return TEntity
     */
    public function findBy(SmartDto $query): ?SmartDto;

    /**
     * @param SmartDto $query
     *
     * @return TEntity
     * @throws SmartDtoException
     * @throws RequiredResultException
     */
    public function findByOrThrow(SmartDto $query): SmartDto;

    /**
     * @param SmartDto $query
     *
     * @return int
     *
     * @throws SmartDtoException
     */
    public function total(SmartDto $query): SmartDto;

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
     * @return object|bool
     */
    public function fetch(Query $query);

    /**
     * @param Query $query
     *
     * @return SmartDto[]
     */
    public function list(Query $query): array;

    /**
     * Query using SmartDto
     *
     * @throws SmartDtoException
     */
    public function toQuery(SmartDto $query): Query;

    /**
     * @param SmartDto $query
     *
     * @return object[]
     * @throws SmartDtoException
     */
    public function query(SmartDto $query): array;

    /**
     * @param SmartDto $query
     *
     * @return TEntity
     * @throws RequiredResultException
     * @throws SmartDtoException
     */
    public function deleteBy(SmartDto $query): SmartDto;

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
    public function resolveEntity(SmartDto $dto): ?SmartDto;

    /**
     * @param SmartDto $dto
     *
     * @return TEntity
     * @throws RequiredResultException
     */
    public function resolveEntityOrThrow(SmartDto $dto): SmartDto;
}
