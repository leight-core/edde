<?php
declare(strict_types=1);

namespace Edde\Phinx;

use Edde\Excel\ExcelImportServiceTrait;
use Edde\Excel\IExcelImportService;
use Edde\File\FileServiceTrait;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Log\LoggerTrait;
use Edde\Slim\SlimApp;
use Edde\Stream\FileStream;
use Edde\Stream\IStream;
use Edde\User\CurrentUserServiceTrait;
use Edde\Uuid\UuidServiceTrait;
use Phinx\Migration\AbstractMigration;
use Throwable;
use function basename;

/**
 * Utility class for Phinx migration which helps with some common stuff like creating
 * UUID prepared tables, foreign keys and so on.
 */
abstract class CommonMigration extends AbstractMigration {
	use JobRepositoryTrait;
	use FileServiceTrait;
	use CurrentUserServiceTrait;
	use UuidServiceTrait;
	use ExcelImportServiceTrait;
	use LoggerTrait;

	public function init() {
		SlimApp::$instance->injectOn($this);
	}

	protected function importExcel(string $file) {
		$this->currentUserService->selectBy('upgrade');
		$this->jobRepository->cleanup();
		FileStream::openRead($file)
			->use(function (IStream $stream) use ($file) {
				$this->excelImportService->import(
					$this->fileService->store($stream, '/import/' . IExcelImportService::class, $this->uuidService->uuid4() . '-' . basename($file), 3600 * 24 * 7)->id
				);
			});
	}

	public function uuid(): string {
		return $this->uuidService->uuid4();
	}

	public function table($tableName, $options = []) {
		$table = new PhinxTable($tableName, $options, $this->getAdapter());
		$this->tables[] = $table;
		return $table;
	}

	public function drop(...$tables) {
		foreach ($tables as $table) {
			$this->table($table)->drop()->save();
		}
	}

	/**
	 * Create a Phinx table with ID column prepared as UUID column.
	 *
	 * @param string $table
	 * @param array  $options
	 *
	 * @return PhinxTable
	 */
	public function createUuidTable(string $table, array $options = []): PhinxTable {
		return $this->table($table, array_merge(
			[
				'id'          => false,
				'primary_key' => ['id'],
			],
			$options))
			->addColumn('id', 'string', [
				'length'    => 36,
				'collation' => 'utf8_unicode_ci',
				'comment'   => 'UUID primary key of the table (full 36 chars).',
			]);
	}

	public function ensureData(string $table, array $data, bool $generateId = true) {
		$this->tryTable($table, function (PhinxTable $table) use ($data, $generateId) {
			foreach ($data as $row) {
				try {
					$table
						->insert([$row], $generateId)
						->saveData();
				} catch (Throwable $throwable) {
					$table->resetData();
					$this->logger->error($throwable);
				}
			}
		});
	}

	public function deleteWhere(string $table, array $conditions) {
		$this->getQueryBuilder()->delete($table)->where($conditions)->execute();
	}

	public function truncate(string $table) {
		$this->deleteWhere($table, []);
	}

	/**
	 * Try changes on table and if fails, reverts changes and logs an error; $->save() is called automatically
	 *
	 * @param string   $table
	 * @param callable $callback
	 */
	public function tryTable(string $table, callable $callback) {
		$table = $this->table($table);
		try {
			$callback($table);
			$table->save();
		} catch (Throwable $throwable) {
			$table->reset();
			$this->logger->error($throwable);
		}
	}
}
