<?php
declare(strict_types=1);

namespace Edde\Phinx;

use Edde\File\FileServiceTrait;
use Edde\Import\ImportMangerTrait;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Log\LoggerTrait;
use Edde\Stream\FileStream;
use Edde\Stream\IStream;
use Edde\User\CurrentUserTrait;
use Edde\Uuid\UuidServiceTrait;
use Phinx\Migration\AbstractMigration;
use Throwable;
use function basename;

/**
 * Utility class for Phinx migration which helps with some common stuff like creating
 * UUID prepared tables, foreign keys and so on.
 */
abstract class CommonMigration extends AbstractMigration {
	use ImportMangerTrait;
	use JobRepositoryTrait;
	use FileServiceTrait;
	use CurrentUserTrait;
	use UuidServiceTrait;
	use LoggerTrait;

	public function init() {
		(require __DIR__ . '/../../../../bootstrap.php')->injectOn($this);
	}

	protected function import(string $service, string $file) {
		$this->currentUser->selectBy('upgrade');
		$this->jobRepository->cleanup();
		FileStream::openRead($file)
			->use(function (IStream $stream) use ($service, $file) {
				$this->importManager->import(
					$service,
					$this->fileService->store($stream, '/import/' . $service, $this->uuidService->uuid4() . '-' . basename($file), 3600 * 24 * 7)->id
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
		$table = $this->table($table);
		try {
			$table
				->insert($data, $generateId)
				->saveData();
		} catch (Throwable $throwable) {
			$table->reset();
			$this->logger->error($throwable);
		}
	}

	public function deleteWhere(string $table, array $conditions) {
		$this->getQueryBuilder()->delete($table)->where($conditions)->execute();
	}

	public function truncate(string $table) {
		$this->deleteWhere($table, []);
	}
}
