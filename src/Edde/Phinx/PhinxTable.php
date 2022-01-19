<?php
declare(strict_types=1);

namespace Edde\Phinx;

use Phinx\Db\Table;
use Ramsey\Uuid\Uuid;
use function array_merge;

class PhinxTable extends Table {
	/**
	 * Create a column prepared to point to an UUID column.
	 *
	 * @param string         $name
	 * @param string         $target
	 * @param array          $options
	 * @param array|string[] $foreignOptions
	 *
	 * @return PhinxTable
	 */
	public function addUuidForeignColumn(string $name, string $target, array $options = [], array $foreignOptions = ['delete' => 'cascade']): PhinxTable {
		$foreignOptions['id'] = $foreignOptions['id'] ?? 'id';
		$this->addColumn($column = ($name . '_id'), 'string', array_merge([
			'length'    => 36,
			'collation' => 'utf8_unicode_ci',
		], $options));
		/**
		 * This is necessary as Phinx cries on an unknown options.
		 */
		$id = $foreignOptions['id'];
		unset($foreignOptions['id']);
		/**
		 * Restrict is the safe option which forces programmer to properly clean all the data or set them to a different options.
		 *
		 * Everything else is quite dangerous.
		 */
		$this->addForeignKey($column, $target, $id, array_merge(['delete' => 'RESTRICT'], $foreignOptions));
		return $this;
	}

	public function addIdForeignColumn(string $name, string $target, array $options = [], array $foreignOptions = ['id' => 'recno']): PhinxTable {
		if (($foreignOptions['delete'] ?? null) === 'SET_NULL') {
			$options['null'] = true;
		}
		$this->addColumn($column = ($name . '_id'), 'integer', $options);
		$id = $foreignOptions['id'];
		unset($foreignOptions['id']);
		$this->addForeignKey($column, $target, $id, $foreignOptions);
		return $this;
	}

	/**
	 * Sane as UUID foreign key, just column is optional (on delete set null).
	 *
	 * @param string         $name
	 * @param string         $target
	 * @param array          $options
	 * @param array|string[] $foreignOptions
	 *
	 * @return PhinxTable
	 */
	public function createOptionalUuidForeignColumn(string $name, string $target, array $options = [], array $foreignOptions = [
		'id'     => 'id',
		'delete' => 'SET_NULL',
	]): PhinxTable {
		return $this->addUuidForeignColumn($name, $target, array_merge(['null' => true], $options), $foreignOptions);
	}

	/**
	 * Simple string column with collation.
	 *
	 * @param string $name    name of the column
	 * @param int    $length  length of string column (varchar length)
	 * @param array  $options column options (goes into Phinx's addColumn).
	 *
	 * @return PhinxTable
	 */
	public function addStringColumn(string $name, int $length = 128, array $options = []): PhinxTable {
		$isUnique = $options['unique'] ?? false;
		unset($options['unique']);
		$this->addColumn($name, 'string', array_merge([
			'length'    => $length,
			'collation' => 'utf8_unicode_ci',
		], $options));
		if ($isUnique) {
			$this->addIndex($name, array_merge([
				'unique' => true,
				'name'   => $this->getName() . '_' . $name . '_unique',
			]));
		}
		return $this;
	}

	public function addTextColumn(string $name, array $options = [], int $length = 4096 * 32,): PhinxTable {
		$this->addColumn('log', 'text', array_merge([
			'length' => $length,
		], $options));
		return $this;
	}

	public function insert($data, bool $generateId = true) {
		foreach ($data as $k => $v) {
			if (!isset($v['id']) && $generateId) {
				$data[$k]['id'] = Uuid::uuid4()->toString();
			}
		}
		return parent::insert($data);
	}

	public function removeIndexByName($name) {
		if ($this->hasIndexByName($name)) {
			parent::removeIndexByName($name);
		}
		return $this;
	}

	public function removeColumn($columnName) {
		if ($this->hasColumn($columnName)) {
			parent::removeColumn($columnName);
		}
		return $this;
	}

	public function dropForeignColumn(string $name): PhinxTable {
		$this
			->dropForeignKey($name)
			->removeColumn($name);
		return $this;
	}
}
