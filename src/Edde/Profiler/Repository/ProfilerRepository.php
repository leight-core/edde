<?php
declare(strict_types=1);

namespace Edde\Profiler\Repository;

use ClanCats\Hydrahon\Query\Sql\Select;
use Edde\Profiler\Dto\ProfilerFilterDto;
use Edde\Query\Dto\Query;
use Edde\Repository\AbstractRepository;
use Edde\Repository\IRepository;

class ProfilerRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(['stamp' => IRepository::ORDER_DESC]);
	}

	public function toQuery(Query $query): Select {
		$select = $this->select();

		/** @var $filter ProfilerFilterDto */
		$filter = $query->filter;
		$filter->fulltext && $this->fulltext($select, [
			'id',
			'name',
		], $filter->fulltext);
		$filter->name && $this->fulltext($select, [
			'name',
		], $filter->name);
		$filter->names && $select->where('name', 'in', $filter->names);

		$this->toOrderBy($query->orderBy, $select);

		return $select;
	}

	public function names() {
		return $this->table()->select('name')->orderBy('name')->distinct()->execute();
	}
}
