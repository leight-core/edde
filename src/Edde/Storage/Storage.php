<?php
declare(strict_types=1);

namespace Edde\Storage;

use ClanCats\Hydrahon\Builder;
use ClanCats\Hydrahon\Query\Sql\Table;
use Dibi\Connection;
use Dibi\DriverException;
use Dibi\Exception;
use Dibi\Fluent;
use Dibi\Result;
use Edde\Storage\Exception\PdoNotAvailableException;
use PDO;
use PDOStatement;
use Throwable;

class Storage {
	use StorageConfigTrait;

	/** @var Connection */
	protected $connection;
	/** @var Builder */
	protected $builder;

	/**
	 * @return Connection
	 *
	 * @throws Exception
	 * @throws \ClanCats\Hydrahon\Exception
	 */
	public function connection(): Connection {
		if (!$this->connection) {
			$this->connection = new Connection($this->storageConfig->getConfig());
			$this->builder = new Builder('mysql', function ($_, string $sql, $params) {
				return $this->connection->query($sql, ...$params);
			});
		}
		return $this->connection;
	}

	public function sql(): Builder {
		$this->connection();
		return $this->builder;
	}

	/**
	 * @param ...$args
	 *
	 * @return Result
	 *
	 * @throws Exception
	 */
	public function query(...$args): Result {
		return $this->connection()->query(...$args);
	}

	/**
	 * @param string $name
	 *
	 * @return Table
	 */
	public function table(string $name): Table {
		return $this->sql()->table($name);
	}

	/**
	 * @param string   $table
	 * @param iterable $data
	 *
	 * @throws Exception
	 */
	public function insert(string $table, iterable $data) {
		$this->connection()->insert($table, $data)->execute();
	}

	/**
	 * @param string     $table
	 * @param array      $data
	 * @param string|int $id
	 *
	 * @return Result|int|null
	 *
	 * @throws Exception
	 */
	public function update(string $table, array $data, $id) {
		unset($data['id']);
		try {
			return $this->updateWhere($table, $data)->where(['recno' => $id])->execute();
		} catch (Throwable $throwable) {
			return $this->updateWhere($table, $data)->where(['id' => $id])->execute();
		}
	}

	/**
	 * Prepare update Fluent.
	 *
	 * @param string   $table
	 * @param iterable $data
	 *
	 * @return Fluent
	 *
	 * @throws Exception
	 */
	public function updateWhere(string $table, iterable $data): Fluent {
		return $this->connection()->update($table, $data);
	}

	/**
	 * @param string $table
	 * @param string $id
	 *
	 * @throws Exception
	 */
	public function delete(string $table, string $id) {
		$this->connection()->delete($table)->where(['id' => $id])->execute();
	}

	/**
	 * @param string $table
	 *
	 * @return Fluent
	 * @throws Exception
	 */
	public function deleteWhere(string $table): Fluent {
		return $this->connection()->delete($table);
	}

	/**
	 * @param callable $block
	 *
	 * @return mixed
	 *
	 * @throws DriverException
	 * @throws Throwable
	 */
	public function transaction(callable $block) {
		$this->connection()->begin();
		try {
			$result = $block();
			$this->connection()->commit();
			return $result;
		} catch (Throwable $exception) {
			$this->connection()->rollback();
			throw $exception;
		}
	}

	/**
	 * Shortcut for translating arguments into a SQL query.
	 *
	 * @param ...$args
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function translate(...$args): string {
		return $this->connection()->translate(...$args);
	}

	/**
	 * Get PDO driver from the connection.
	 *
	 * @return PDO
	 *
	 * @throws Exception
	 * @throws PdoNotAvailableException
	 */
	public function getPdo(): PDO {
		$connection = $this->connection();
		if (!($pdo = $connection->getDriver()->getResource()) instanceof PDO) {
			throw new PdoNotAvailableException(sprintf('Unknown connection driver [%s]; expected PDO (for BLOB support).', gettype($pdo)));
		}
		return $pdo;
	}

	public function statement(...$args): PDOStatement {
		return $this->getPdo()->prepare($this->translate(...$args));
	}
}
