<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use ClanCats\Hydrahon\Query\Sql\SelectBase;
use Edde\Job\Dto\Log\JobLogFilterDto;
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

	public function applyWhere($filter, SelectBase $selectBase): void {
		/** @var $filter JobLogFilterDto */
		parent::applyWhere($filter, $selectBase);
		isset($filter->jobId) && $selectBase->where('job_id', $filter->jobId);
		isset($filter->id) && $selectBase->where('id', $filter->id);
		isset($filter->type) && $selectBase->where('type', 'in', $filter->type);
		isset($filter->notType) && $selectBase->whereNotIn('type', $filter->notType);
		isset($filter->level) && $selectBase->where('level', 'in', $filter->level);
	}

	public function hasLog(string $jobId): bool {
		return $this->select()->where('job_id', $jobId)->where('level', '>', 0)->fields(null)->addFieldCount('id')->execute()->fetchSingle() > 0;
	}
}
