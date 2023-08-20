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

	protected $table;
	protected $id;
	protected $orderBy = [];
	protected $fulltextOf = [];
	protected $searchOf = [];
	protected $matchOf = [];

	public function __construct(string $table = null, string $id = 'id') {
		$this->table = $table ?? 'z_' . StringUtils::recamel(Arrays::last(explode('\\', str_replace('Repository', '', static::class))), '_');
		$this->id = $id;
	}

	/**
	 * @inheritDoc
	 */
	public function create(SmartDto $dto, bool $raw = true) {
		$this
			->connection
			->getConnection()
			->insert(
				$this->table,
				(array)$dto->export($raw)
			);
		return $dto;
	}

	/**
	 * @inheritDoc
	 */
	public function upsert(SmartDto $dto) {
		try {
			/**
			 * Patch contains entity resolution, so if it fails,
			 * we can try create a new entity.
			 */
			return $this->update($dto);
		} catch (RepositoryException|SmartDtoException $exception) {
			return $this->create($dto->getSmartDto('create', true));
		}
	}

	/**
	 * @inheritDoc
	 */
	public function update(SmartDto $dto) {
		$dto->ensure([
			'filter',
			'update',
		]);
		$this
			->connection
			->getConnection()
			->update(
				$this->table,
				(array)$dto->getSmartDto('update')->export(true),
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
		if (!($entity = $this->queryOf()->select()->from($this->table)->where([
			$this->id,
			$id,
		])->execute())) {
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
		if (!($entity = $this->toQuery($query)->execute()->fetch())) {
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
		return (int)$builder->execute()->execute();
	}

	public function queryOf(): Query {
		return $this->connection->getConnection()->newQuery();
	}

	/**
	 * @inheritDoc
	 */
	public function toQuery(SmartDto $query): Query {
		$this->smartService->check($query, QuerySchema::class);
		$cursor = $query->getSmartDto('cursor', true);
		$queryBuilder = $this
			->queryOf()
			->select()
			->from($this->table);
		$cursor->knownWithValue('page') && $queryBuilder->page($cursor->getValue('page') + 1, $cursor->getSafeValue('size'));
		$this->applyQuery($query, $queryBuilder);
		return $queryBuilder;
	}

	/**
	 * @inheritDoc
	 */
	public function query(string $alias, SmartDto $query): StatementInterface {
		return $this->toQuery($query)->execute();
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