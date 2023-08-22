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
use Edde\Dto\Mapper\ImportMapperTrait;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Mapper\AbstractMapper;
use Edde\Math\RandomServiceTrait;
use Edde\Query\Schema\QuerySchema;
use Edde\Schema\ISchema;
use Edde\Schema\SchemaLoaderTrait;
use Edde\Utils\StringUtils;
use Nette\Utils\Arrays;

/**
 * @template         TEntity of object
 * @template         TFilter of object
 *
 * @template-extends IRepository<TEntity, TFilter>
 */
abstract class AbstractRepository extends AbstractMapper implements IRepository {
	use ConnectionTrait;
	use RandomServiceTrait;
	use SmartServiceTrait;
	use SchemaLoaderTrait;
	use ImportMapperTrait;
	use ExportMapperTrait;

	/**
	 * @var ISchema
	 */
	private $_schema;
	protected $schema;
	protected $table;
	protected $id = 'id';
	protected $orderBy = [];
	protected $fulltextOf = [];
	protected $searchOf = [];
	protected $matchOf = [];

	public function __construct(string $schema) {
		$this->schema = $schema;
		$this->table = $table ?? 'z_' . StringUtils::recamel(Arrays::last(explode('\\', str_replace('Repository', '', static::class))), '_');
	}

	public function getSchema(): ISchema {
		return $this->_schema ?? $this->_schema = $this->schemaLoader->load($this->schema);
	}

	/**
	 * @inheritDoc
	 */
	public function create(SmartDto $dto, bool $raw = false): SmartDto {
		$this
			->connection
			->getConnection()
			->insert(
				$this->table,
				(array)$this->exportMapper->item($dto, ['raw' => $raw])
			);
		return $this->find($dto->getValue('id'));
	}

	/**
	 * @inheritDoc
	 */
	public function upsert(SmartDto $dto, bool $raw = false): SmartDto {
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
	public function update(SmartDto $dto, bool $raw = false): SmartDto {
		$dto->ensure([
			'filter',
			'update',
		]);
		$update = (array)$this->exportMapper->item($dto->getSmartDto('update'), ['raw' => $raw]);
		unset($update[$this->id]);
		$this
			->connection
			->getConnection()
			->update(
				$this->table,
				$update,
				[
					$this->id => $id = $this->resolveEntityOrThrow($dto)->getValue('id'),
				]
			);
		return $this->find($id);
	}

	/**
	 * @inheritDoc
	 */
	public function find(string $id, string $message = null): SmartDto {
		if (!($entity = $this->fetch($this->select()->where([$this->id => $id])))) {
			throw new RequiredResultException($message ?? sprintf('Cannot find id [%s] in [%s]!', $id, static::class), 500);
		}
		return $this->item($entity);
	}

	public function findBy(SmartDto $query): ?SmartDto {
		try {
			return $this->findByOrThrow($query);
		} catch (RequiredResultException $exception) {
			return null;
		}
	}

	public function findByOrThrow(SmartDto $query): SmartDto {
		if (!($entity = $this->fetch($this->toQuery($query)))) {
			throw new RequiredResultException('Cannot find an entity by query.');
		}
		return $this->item($entity);
	}

	/**
	 * @inheritDoc
	 */
	public function total(SmartDto $query): int {
		$builder = $this->queryOf();
		$builder
			->select(['count' => $builder->func()->count($this->id)])
			->from($this->table);
		$query->knownWithValue('filter') && $this->applyWhere($query->getSmartDto('filter'), $query, $builder);
		return (int)$this->fetch($builder)->count;
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
		return array_map([
			$this,
			'item',
		], $query->execute()->fetchAll(StatementInterface::FETCH_TYPE_OBJ));
	}

	/**
	 * @inheritDoc
	 */
	public function toQuery(SmartDto $query): Query {
		$this->smartService->check($query, QuerySchema::class);
		$cursor = $query->getSmartDto('cursor', true);
		$builder = $this->select();
		$cursor->knownWithValue('page') && $builder->page($cursor->getValue('page') + 1, $cursor->getSafeValue('size'));
		return $this->applyQuery($query, $builder);
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
	public function deleteBy(SmartDto $query): SmartDto {
		$entity = $this->find($query->getValue('id'));
		$this
			->connection
			->getConnection()
			->delete(
				$this->table,
				[$this->id => $entity->getValue('id')]
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
	public function resolveEntity(SmartDto $dto): ?SmartDto {
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
	public function resolveEntityOrThrow(SmartDto $dto): SmartDto {
		if (!($entity = $this->resolveEntity($dto))) {
			throw new RequiredResultException(sprintf('Cannot resolve entity from DTO [%s].', $dto->getName()));
		}
		return $entity;
	}

	/**
	 * Overall QueryBuilder mutator for this repository
	 *
	 * @param SmartDto $query
	 * @param Query    $builder
	 *
	 * @return void
	 * @throws SmartDtoException
	 */
	protected function applyQuery(SmartDto $query, Query $builder): Query {
		$builder = $this->applyQueryBuilder($builder);
		$this->applyWhere($query->getSmartDto('filter', true), $query, $builder);
		$this->applyOrderBy($query->getSmartDto('orderBy', true), $query, $builder);
		return $builder;
	}

	protected function applyQueryBuilder(Query $query): Query {
		return $query;
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
		foreach ($this->fulltextOf as $value => $field) {
			if ($filter->knownWithValue($value)) {
				$this->fulltextOf($builder, $field, $filter->getValue($value));
			}
		}
		$filter->knownWithValue('fulltext') && !empty($this->searchOf) && $this->searchOf($builder, $filter->getValue('fulltext'), $this->searchOf);
		foreach ($this->matchOf as $value => $field) {
			$filter->knownWithValue($value) && $this->matchOf($builder, $field, $filter->getValue($value));
		}
	}

	protected function applyOrderBy(SmartDto $orderBy, SmartDto $query, Query $builder): void {
		if (!empty($export = (array)$orderBy->export())) {
			foreach ($export as $k => $v) {
				$builder->order([
					$this->field($k) => in_array($orderBy = strtoupper($v), [
						'ASC',
						'DESC',
					]) ? $orderBy : 'ASC',
				]);
			}
			return;
		}
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
		$query->andWhere(function (QueryExpression $expression) use ($field, $value) {
			return $expression->like($this->field($field), "%$value%");
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
		$query->andWhere(function (QueryExpression $expression) use ($field, $value) {
			$field = $this->field($field);
			return $value === null ? $expression->isNull($field) : $expression->eq($field, $value);
		});
	}

	protected function matchOfIn(Query $query, string $field, array $values) {
		$query->andWhere(function (QueryExpression $expression) use ($field, $values) {
			return $expression->in(
				$this->field($field),
				$values
			);
		});
	}

	protected function searchOf(Query $query, string $value, array $fields) {
		$query->andWhere(function (QueryExpression $expression) use ($value, $fields) {
			return $expression->or(array_map(function ($field) use ($expression, $value) {
				return $expression->or([])->like($this->field($field), "%$value%");
			}, $fields));
		});
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

	public function item($item, $params = null) {
		return $this->importMapper->item($item, ['schema' => $this->getSchema()]);
	}
}
