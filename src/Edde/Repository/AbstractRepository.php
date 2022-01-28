<?php
declare(strict_types=1);

namespace Edde\Repository;

use ClanCats\Hydrahon\Query\Expression;
use ClanCats\Hydrahon\Query\Sql\Delete;
use ClanCats\Hydrahon\Query\Sql\Exception as CattyException;
use ClanCats\Hydrahon\Query\Sql\Select;
use ClanCats\Hydrahon\Query\Sql\SelectBase;
use ClanCats\Hydrahon\Query\Sql\Table;
use Dibi\Exception;
use Dibi\Result;
use Dibi\Row;
use Dibi\UniqueConstraintViolationException;
use Edde\Diff\DiffServiceTrait;
use Edde\Mapper\IMapper;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Repository\Exception\DuplicateEntryException;
use Edde\Repository\Exception\RepositoryException;
use Edde\Repository\Exception\RequiredResultException;
use Edde\Storage\StorageTrait;
use Edde\Utils\StringUtils;
use Edde\Uuid\UuidServiceTrait;
use Nette\Utils\Arrays;
use Throwable;
use function array_filter;
use function array_map;
use function sprintf;
use function str_replace;
use function strpos;

abstract class AbstractRepository implements IRepository {
	use DiffServiceTrait;
	use StorageTrait;
	use UuidServiceTrait;

	/** @var string */
	protected $table;
	/** @var string */
	protected $orderBy;
	/** @var string[] */
	protected $unique;
	/** @var string */
	protected $id;

	public function __construct(array $orderBy = null, array $unique = [], string $id = "id", string $table = null) {
		$this->table = $table ?? 'z_' . StringUtils::recamel(Arrays::last(explode('\\', str_replace('Repository', '', static::class))), '_');
		$this->orderBy = array_map(function (string $orderBy) {
			return $this->table . '.' . $orderBy;
		}, $orderBy ?? []);
		$this->unique = array_map([
			$this,
			'resolveColumn',
		], $unique);
		$this->id = $id;
	}

	/**
	 * @param string|int  $id
	 * @param string|null $table
	 *
	 * @return mixed
	 *
	 * @throws RepositoryException
	 * @throws CattyException
	 */
	public function find($id, string $table = null) {
		return $this->findById($id, $table);
	}

	/**
	 * @param string $search
	 * @param string $message
	 *
	 * @return Row
	 *
	 * @throws RequiredResultException
	 */
	public function require(string $search, string $message): Row {
		$row = $this->query(Query::create([
			'size'   => 1,
			'page'   => 0,
			'filter' => (object)['fulltext' => $search],
		]))->fetch();
		if (!$row) {
			throw new RequiredResultException($message);
		}
		return $row;
	}

	/**
	 * @param string|int  $id
	 * @param string|null $table
	 *
	 * @return mixed
	 *
	 * @throws RepositoryException
	 * @throws CattyException
	 */
	protected function findById($id, string $table = null) {
		$table = $table ?? $this->table;
		if (!($fetch = $this->select()->where($this->id, $id)->execute()->fetch())) {
			throw new RepositoryException(sprintf('Cannot find [%s] by [%s]!', $table, $id), 500);
		}
		return $fetch;
	}

	protected function generateId(): string {
		return $this->uuidService->uuid4();
	}

	/**
	 * Return total count of items in the repository.
	 *
	 * @param Query $query
	 *
	 * @return int
	 *
	 */
	public function total(Query $query): int {
		return $this->toQuery($query)->page(0, 1)->fields(null)->addFieldCount(new Expression('distinct ' . $this->table . '.' . $this->id))->execute()->fetchSingle();
	}

	/**
	 * Return the given page from the dataset.
	 *
	 * @param Query $query
	 *
	 * @return Result
	 */
	public function query(Query $query): iterable {
		return $this->toQuery($query)->page($query->page, $query->size)->execute();
	}

	/**
	 * @param Query $query
	 *
	 * @return Select
	 */
	public function toQuery(Query $query): Select {
		return $this->select();
	}

	/**
	 * Just a shortcut method for storage query.
	 *
	 * @param ...$args
	 *
	 * @return Result
	 *
	 * @throws Exception
	 */
	protected function native(...$args): Result {
		return $this->storage->query(...$args);
	}

	/**
	 * Low level method for inserting data into the repository; Id is generated automatically (using UUIDv4).
	 *
	 * @param array       $data
	 * @param string|null $table
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 * @throws Throwable
	 */
	public function insert(array $data, string $table = null) {
		$table = $table ?? $this->table;
		$increment = ($data['id'] ?? null) === -1;
		if (empty($data['id'])) {
			$data['id'] = $this->generateId();
		}
		$increment && (function () use (&$data) {
			unset($data['id']);
		})();
		try {
			$this->storage->insert($table, $data);
			$increment && ($data['id'] = $this->storage->connection()->getInsertId());
			return $this->findById($data['id'], $table);
		} catch (Throwable $e) {
			$this->exception($e);
		}
	}

	/**
	 * Try to change the given data; an ID **must** exist in data.
	 *
	 * @param array $data
	 *
	 * @return mixed
	 *
	 * @throws DuplicateEntryException
	 * @throws RepositoryException
	 * @throws Throwable
	 */
	public function change(array $data) {
		if (empty($data['id'])) {
			throw new RepositoryException(sprintf('Missing ID for an update of [%s]!', $this->table));
		}
		try {
			$this->storage->update($this->table, $data, $data['id']);
			return $this->find($data['id']);
		} catch (Throwable $e) {
			$this->exception($e);
		}
	}

	public function exception(Throwable $throwable) {
		try {
			throw $throwable;
		} catch (UniqueConstraintViolationException $e) {
			foreach ($this->unique as $unique) {
				if (strpos($e->getMessage(), $unique) !== false) {
					throw new DuplicateEntryException(sprintf('Duplicate entry [%s] of [%s].', $unique, $this->table), 0, $e);
				}
			}
			throw new DuplicateEntryException(sprintf('Duplicate entry [unknown] of [%s].', $this->table), 0, $e);
		} catch (Throwable $throwable) {
			throw $throwable;
		}
	}

	/**
	 * Patch filters out NULL values (thus patch - update just present values).
	 *
	 * @param array $data
	 *
	 * @return mixed
	 *
	 * @throws RepositoryException
	 * @throws Throwable
	 */
	public function patch(array $data) {
		return $this->change($data);
	}

	/**
	 * @param string $id
	 *
	 * @return array|Row|null
	 *
	 * @throws Throwable
	 */
	public function delete(string $id) {
		$data = $this->find($id);
		$this->storage->delete($this->table, $id);
		return $data;
	}

	public function deleteWhere(): Delete {
		return $this->table()->delete();
	}

	public function truncate(): void {
		$this->table()->delete()->execute();
	}

	/**
	 * @return Result
	 */
	public function all(): Result {
		return $this->select()->execute();
	}

	public function toResult(Query $query, IMapper $mapper): QueryResult {
		return new QueryResult(
			$this->total($query),
			$query->size,
			$mapper->map($this->query($query) ?? [])
		);
	}

	/**
	 * @param string     $source
	 * @param string     $target
	 * @param string     $id
	 * @param array|null $items
	 *
	 * @throws Exception
	 * @throws Throwable
	 * @throws UniqueConstraintViolationException
	 */
	protected function syncWith(string $source, string $target, string $id, ?array $items) {
		$this->table()->delete()->where([$source => $id])->execute();
		foreach (array_filter($items ?? []) as $item) {
			$this->insert([
				$source => $id,
				$target => $item,
			]);
		}
	}

	public function table(): Table {
		return $this->storage->table($this->table);
	}

	public function select($fields = null): Select {
		return $this->table()->select($fields ?? $this->table . '.*')->orderBy($this->toBy($this->orderBy));
	}

	protected function toBy(?array $orderBy, string $table = null): ?array {
		return $orderBy ? array_combine(array_map(function (string $order) use ($table) {
			return ($table ?? $this->table) . '.' . $order;
		}, array_keys($orderBy)), array_map(function (bool $order) {
			return $order ? 'asc' : 'desc';
		}, $orderBy)) : [];
	}

	protected function toOrderBy($orderBy, Select $select) {
		if (!$orderBy) {
			return;
		}
		if (!empty($orders = (array)($orderBy))) {
			$select->orderBy(null);
		}
		foreach ($orders as $order => $value) {
			$value !== null && $select->orderBy($this->toBy([
				$order => $value,
			]));
		}
	}

	protected function fulltext(Select $select, array $columns, $values): Select {
		return $select->where(function (SelectBase $select) use ($columns, $values) {
			foreach ((array)$values as $value) {
				foreach ($columns as $column) {
					$select->where($this->resolveColumn($column), 'like', '%' . $value . '%', 'or');
				}
			}
		});
	}

	protected function resolveColumn(string $name): string {
		return str_replace(
			['$'],
			[$this->table],
			$name
		);
	}
}
