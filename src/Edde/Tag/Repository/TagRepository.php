<?php
declare(strict_types=1);

namespace Edde\Tag\Repository;

use ClanCats\Hydrahon\Query\Sql\Select;
use Dibi\Exception;
use Edde\Query\Dto\Query;
use Edde\Repository\AbstractRepository;
use Edde\Repository\Exception\DuplicateEntryException;
use Edde\Repository\IRepository;
use Edde\Tag\Dto\TagFilterDto;

class TagRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(['sort' => IRepository::ORDER_ASC], ['z_tag_code_unique']);
	}

	public function toQuery(Query $query): Select {
		$select = parent::toQuery($query);

		/** @var $filter TagFilterDto */
		$filter = $query->filter;
		!empty($filter->groups) && $this->where($select, '$.group', 'in', $filter->groups);

		$this->toOrderBy($query->orderBy, $select);

		return $select;
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

	public function ensure(string $code, string $group, int $sort = 0) {
		try {
			return $this->insert([
				'code'  => $code,
				'label' => $code,
				'group' => $group,
				'sort'  => $sort,
			]);
		} catch (DuplicateEntryException $exception) {
			return $this->select()->where('code', $code)->where('group', $group)->execute()->fetch();
		}
	}
}
