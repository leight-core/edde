<?php
declare(strict_types=1);

namespace Edde\Database\Repository;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\Query;
use Cake\Database\StatementInterface;
use Edde\Database\Connection\ConnectionTrait;
use Edde\Database\Exception\RepositoryException;
use Edde\Database\Exception\RequiredResultException;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\Mapper\ExportMapperTrait;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Math\RandomServiceTrait;
use Edde\Query\Schema\QuerySchema;
use Edde\Utils\StringUtils;
use Nette\Utils\Arrays;

/**
 * @template         TEntity of object
 * @template         TFilter of object
 *
 * @template-extends IRepository<TEntity, TFilter>
 */
abstract class AbstractRepository implements IRepository {
	use ConnectionTrait;
	use RandomServiceTrait;
	use SmartServiceTrait;
	use ExportMapperTrait;

	protected $table;
	protected $id = 'id';
	protected $orderBy = [];
	protected $fulltextOf = [];
	protected $searchOf = [];
	protected $matchOf = [];

	public function __construct() {
		$this->table = $table ?? 'z_' . StringUtils::recamel(Arrays::last(explode('\\', str_replace('Repository', '', static::class))), '_');
	}

	/**
	 * @inheritDoc
	 */
	public function create(SmartDto $dto, bool $raw = false) {
		$this
			->connection
			->getConnection()
			->insert(
				$this->table,
				(array)$this->exportMapper->item($dto, ['raw' => $raw])
			);
		return $dto;
	}

	/**
	 * @inheritDoc
	 */
	public function upsert(SmartDto $dto, bool $raw = false) {
		try {
			/**
			 * Patch contains entity resolution, so if it fails,
			 * we can try create a new entity.
			 */
			return $this->update($dto, $raw);
		} catch (RepositoryException|SmartDtoException $exception) {
			return $this->create($dto->getSmartDto('create', true), $raw);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function update(SmartDto $dto, bool $raw = false) {
		$dto->ensure([
			'filter',
			'update',
		]);
		$this
			->connection
			->getConnection()
			->update(
				$this->table,
				(array)$this->exportMapper->item($dto->getSmartDto('update'), ['raw' => $raw]),
				[
					$this->id => $id = $this->resolveEntityOrThrow($dto)->{$this->id},
				]
			);
		return $this->find($id);
	}

	/**
	 * @inheritDoc
	 */
	public function find(string $id, string $message = null) {
		if (!($entity = $this->fetch($this->select()->where([$this->id => $id])))) {
			throw new RequiredResultException($message ?? sprintf('Cannot find id [%s] in [%s]!', $id, static::class), 500);
		}
		return $entity;
	}

	public function findBy(SmartDto $query) {
		try {
			return $this->findByOrThrow($query);
		} catch (RequiredResultException $exception) {
			return null;
		}
	}

	public function findByOrThrow(SmartDto $query) {
		if (!($entity = $this->fetch($this->toQuery($query)))) {
			throw new RequiredResultException('Cannot find an entity by query.');
		}
		return $entity;
	}

	/**
	 * @inheritDoc
	 */
	public function total(SmartDto $query): int {
		$builder = $this->queryOf();
		$builder
			->select(["count" => $builder->func()->count($this->id)])
			->from($this->table);
		$query->knownWithValue('filter') && $this->applyWhere($query->getSmartDto('filter'), $query, $builder);
		return $this->fetch($builder)->count;
	}

	public function queryOf(): Query {
		return $this->connection->getConnection()->newQuery();
	}

	public function select(array $fields = [], bool $override = false): Query {
		return $this->queryOf()->select(array_merge([$this->field('$.*')], $fields), $override)->from($this->table);
	}

	public function fetch(Query $query) {
		return $query->execute()->fetch(StatementInterface::FETCH_TYPE_OBJ);
	}

	public function list(Query $query): array {
		return $query->execute()->fetchAll(StatementInterface::FETCH_TYPE_OBJ);
	}

	/**
	 * @inheritDoc
	 */
	public function toQuery(SmartDto $query): Query {
		$this->smartService->check($query, QuerySchema::class);
		$cursor = $query->getSmartDto('cursor', true);
		$builder = $this->select();
		$cursor->knownWithValue('page') && $builder->page($cursor->getValue('page') + 1, $cursor->getSafeValue('size'));
		$this->applyQuery($query, $builder);
		return $builder;
	}

	/**
	 * @inheritDoc
	 */
	public function query(SmartDto $query): array {
		return $this->list($this->toQuery($query));
	}

	/**
	 * @inheritDoc
	 */
	public function deleteBy(SmartDto $query) {
		$entity = $this->find($query->getValue('id'));
		$this
			->connection
			->getConnection()
			->delete(
				$this->table,
				[$this->id => $entity->id]
			)
			->execute();
		return $entity;
	}

	public function deleteWith(SmartDto $query): void {
		$this->applyWhere(
			$query->getSmartDto('filter', true),
			$query,
			$query = $this->queryOf()->delete($this->table)
		);
		$query->execute();
	}

	/**
	 * @inheritDoc
	 */
	public function resolveEntity(SmartDto $dto) {
		try {
			if ($id = $dto->getSmartDto('filter', true)->getSafeValue('id')) {
				return $this->find($id);
			}
			return null;
		} catch (RequiredResultException $exception) {
			return null;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function resolveEntityOrThrow(SmartDto $dto) {
		if (!($entity = $this->resolveEntity($dto))) {
			throw new RequiredResultException(sprintf('Cannot resolve entity from DTO [%s].', $dto->getName()));
		}
		return $entity;
	}

	/**
	 * Overall QueryBuilder mutator for this repository
	 *
	 * @param SmartDto $query
	 * @param Query    $build
	 *
	 * @return void
	 * @throws SmartDtoException
	 */
	protected function applyQuery(SmartDto $query, Query $build): void {
		$this->applyWhere($query->getSmartDto('filter', true), $query, $build);
		$this->applyOrderBy($query->getSmartDto('orderBy', true), $query, $build);
	}

	/**
	 * @param SmartDto $filter
	 * @param SmartDto $query
	 * @param Query    $builder
	 *
	 * @return void
	 * @throws SmartDtoException
	 */
	protected function applyWhere(SmartDto $filter, SmartDto $query, Query $builder): void {
		foreach ($this->fulltextOf as $field => $value) {
			if ($filter->knownWithValue($field)) {
				$this->fulltextOf($builder, $field, $filter->getValue($field));
			}
		}
		$filter->knownWithValue('fulltext') && !empty($this->searchOf) && $this->searchOf($builder, $filter->getValue('fulltext'), $this->searchOf);
		foreach ($this->matchOf as $field => $value) {
			$filter->knownWithValue($field) && $this->matchOf($builder, $field, $filter->getValue($field));
		}
	}

	protected function applyOrderBy(SmartDto $orderBy, SmartDto $query, Query $builder): void {
		foreach ($this->orderBy as $name => $order) {
			if (is_string($order) && !in_array($order = strtoupper($order), [
					'ASC',
					'DESC',
				])) {
				$order = 'ASC';
			} else if (is_bool($order)) {
				$order = $order ? 'ASC' : 'DESC';
			} else if (!is_string($order)) {
				$order = 'ASC';
			}
			$builder->order([$this->field($name) => $order]);
		}
	}

	/**
	 * Helper function to generate "fulltext" where condition using LIKE
	 *
	 * @param Query  $query
	 * @param string $field
	 * @param string $value
	 *
	 * @return void
	 */
	protected function fulltextOf(Query $query, string $field, string $value) {
		$query->andWhere(function (QueryExpression $exp) use ($field, $value) {
			$exp->like($this->field($field), "%$value%");
		});
	}

	/**
	 * Helper method to generate exact match where condition
	 *
	 * @param Query  $query
	 * @param string $field
	 * @param mixed  $value
	 *
	 * @return void
	 */
	protected function matchOf(Query $query, string $field, $value) {
		$query->andWhere([$this->field($field) => $value]);
	}

	protected function searchOf(Query $query, string $value, array $fields) {
//		$query->andWhere($query->expr()->orX(...array_map(function (string $field) use ($query, $value, $alias) {
//			return $query->expr()->like($this->field($field, $alias), ':' . $this->paramOf($query, "%$value%"));
//		}, $fields)));
	}

	/**
	 * Translate the given field with the given alias
	 *
	 * @param string      $field
	 * @param string|null $alias
	 *
	 * @return string
	 */
	protected function field(string $field, string $alias = null): string {
		return str_replace('$', $alias ?? $this->table, $field);
	}
}
