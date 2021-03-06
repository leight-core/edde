<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use ClanCats\Hydrahon\Query\Sql\Select;
use Edde\Job\Dto\Log\JobLogFilterDto;
use Edde\Query\Dto\Query;
use Edde\Repository\AbstractRepository;
use Edde\Repository\IRepository;

class JobLogRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(['stamp' => IRepository::ORDER_ASC]);
	}

	public function log(string $jobId, int $level, string $message, $context = null, string $type = null, string $reference = null) {
		return $this->insert([
			'job_id'    => $jobId,
			'level'     => $level,
			'message'   => $message,
			'item'      => json_encode($context),
			'stamp'     => microtime(true),
			'reference' => $reference ?? $itemDto->index ?? null,
			'type'      => $type ?? "common",
		]);
	}

	public function toQuery(Query $query): Select {
		$select = $this->select();

		/** @var $filter JobLogFilterDto */
		$filter = $query->filter;
		isset($filter->jobId) && $select->where('job_id', $filter->jobId);
		isset($filter->id) && $select->where('id', $filter->id);
		isset($filter->type) && $select->where('type', 'in', $filter->type);
		isset($filter->notType) && $select->whereNotIn('type', $filter->notType);
		isset($filter->level) && $select->where('level', 'in', $filter->level);

		$this->toOrderBy($query->orderBy, $select);

		return $select;
	}

	public function hasLog(string $jobId): bool {
		return $this->select()->where('job_id', $jobId)->where('level', '>', 0)->fields(null)->addFieldCount('id')->execute()->fetchSingle() > 0;
	}
}
