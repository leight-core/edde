<?php
declare(strict_types=1);

namespace Edde\Log\Repository;

use ClanCats\Hydrahon\Query\Sql\Select;
use ClanCats\Hydrahon\Query\Sql\SelectBase;
use DateTime;
use Dibi\Exception;
use Edde\Log\Dto\Create\CreateDto;
use Edde\Log\Dto\LogFilterDto;
use Edde\Query\Dto\Query;
use Edde\Repository\AbstractRepository;
use Edde\Repository\IRepository;
use Throwable;

class LogRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(['microtime' => IRepository::ORDER_DESC]);
	}

	/**
	 * @param CreateDto $createDto
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 * @throws Throwable
	 */
	public function create(CreateDto $createDto) {
		return $this->insert([
			'log'       => $createDto->log,
			'type'      => $createDto->type,
			'trace'     => $createDto->traceId,
			'reference' => $createDto->referenceId,
			'stack'     => $createDto->stack,
			'microtime' => microtime(true),
			'stamp'     => new DateTime(),
			'user_id'   => $createDto->userId,
			'context'   => json_encode($createDto->context),
		]);
	}

	public function toQuery(Query $query): Select {
		$select = $this->select();

		/** @var $filter LogFilterDto */
		$filter = $query->filter;
		!empty($filter->types) && $select->where('type', 'in', $filter->types);
		!empty($filter->userIds) && $select->where('user_id', 'in', $filter->userIds);
		isset($filter->reference) && $select->where(function (SelectBase $selectBase) use ($filter) {
			$selectBase->orWhere('trace', $filter->reference);
			$selectBase->orWhere('reference', $filter->reference);
		});
		isset($filter->stamp) && $filter->stamp->from && $select->where('stamp', '>=', $filter->stamp->from);
		isset($filter->stamp) && $filter->stamp->to && $select->where('stamp', '<=', $filter->stamp->to);
		!empty($filter->tagIds) && $select->where('tag_id', 'in', $filter->tagIds);

		$this->toOrderBy($query->orderBy, $select);

		return $select->distinct();
	}
}
