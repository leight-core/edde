<?php
declare(strict_types=1);

namespace Edde\Tag\Repository;

use Dibi\Exception;
use Edde\Repository\AbstractRepository;
use Edde\Repository\Exception\DuplicateEntryException;
use Edde\Repository\IRepository;

class TagRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(['code' => IRepository::ORDER_ASC], ['z_tag_code_group_unique']);
	}

	/**
	 * @param $code
	 *
	 * @return array|null
	 *
	 * @throws Exception
	 */
	public function findByCode($code) {
		return $this->native("SELECT * FROM %n WHERE %and", $this->table, ['code' => $code])->fetch();
	}

	public function fetchByGroup(string $group) {
		return $this->native("SELECT * FROM %n WHERE %and ORDER BY sort", $this->table, ['group' => $group]);
	}

	public function ensure(string $code, string $group) {
		try {
			return $this->insert([
				'code'  => $code,
				'label' => $code,
				'group' => $group,
				'sort'  => 0,
			]);
		} catch (DuplicateEntryException $exception) {
			return $this->select()->where('code', $code)->where('group', $group)->execute()->fetch();
		}
	}
}
