<?php
declare(strict_types=1);

namespace Edde\Config\Repository;

use ClanCats\Hydrahon\Query\Sql\SelectBase;
use Dibi\Exception;
use Edde\Config\Dto\ConfigFilterDto;
use Edde\Config\Dto\Create\CreateDto;
use Edde\Repository\AbstractRepository;
use Edde\Repository\Exception\DuplicateEntryException;
use Edde\Repository\Exception\RepositoryException;
use Edde\Repository\IRepository;
use Throwable;

class ConfigRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(['key' => IRepository::ORDER_ASC], [
			'z_config_key_unique',
		]);
	}

	public function applyWhere($filter, SelectBase $selectBase): void {
		/** @var $filter ConfigFilterDto */
		$filter->fulltext && $this->fulltext($selectBase, [
			'id',
			'key',
			'value',
		], $filter->fulltext);
		isset($filter->id) && $this->where($selectBase, 'id', $filter->id);
		isset($filter->private) && $this->where($selectBase, 'private', $filter->private);
	}

	/**
	 * @param string $key
	 *
	 * @return array
	 *
	 * @throws \ClanCats\Hydrahon\Query\Sql\Exception
	 */
	public function findByKey(string $key) {
		return $this->select()->where('key', $key)->execute()->fetch();
	}

	/**
	 * Ensure the given key with a value exists; just creates a new records not
	 * updating existing ones.
	 *
	 * @param string     $key
	 * @param mixed|null $value
	 *
	 * @throws RepositoryException
	 * @throws Throwable
	 */
	public function ensure(string $key, $value) {
		try {
			$this->insert([
				'key'   => $key,
				'value' => $value,
			]);
		} catch (DuplicateEntryException $exception) {
			// silence on the road - noop and swallow is intentional
		}
	}

	public function update(string $key, $value) {
		try {
			$this->insert([
				'key'   => $key,
				'value' => $value,
			]);
		} catch (DuplicateEntryException $exception) {
			$this->change([
				'id'    => $this->findByKey($key)->id,
				'value' => $value,
			]);
		}
	}

	/**
	 * @param CreateDto $createDto
	 *
	 * @return array
	 *
	 * @throws Exception
	 * @throws Throwable
	 */
	public function create(CreateDto $createDto) {
		return $this->insert([
			'key'     => $createDto->config->key,
			'value'   => $createDto->config->value,
			'private' => $createDto->config->private,
		]);
	}
}
