<?php
declare(strict_types=1);

namespace Edde\Job\Mapper;

use Edde\Job\Repository\JobLogRepositoryTrait;
use Edde\Job\Schema\Job\JobSchema;
use Edde\Mapper\AbstractMapper;
use Edde\Process\ProcessServiceTrait;

class JobMapper extends AbstractMapper {
	use JobLogRepositoryTrait;
	use ProcessServiceTrait;

	public function item($item, $params = null) {
		return $this->smartService->from($item, JobSchema::class);

//		return $this->dtoService->fromArray(JobDto::class, [
//			'id'          => $item->id,
//			'service'     => $item->service,
//			'params'      => $item->params ? json_decode($item->params) : null,
//			'result'      => $item->result ? json_decode($item->result) : null,
//			'total'       => $item->total,
//			'success'     => $item->success,
//			'count'       => $item->success + $item->error,
//			'ratio'       => (100 * $item->success) / max($item->total, 1),
//			'error'       => $item->error,
//			'progress'    => $item->progress,
//			'runtime'     => $item->runtime,
//			'performance' => $item->performance,
//			'status'      => $item->status,
//			'created'     => $this->isoDateNull($item->created),
//			'done'        => $this->isoDateNull($item->done),
//			'formatted'   => sprintf('%.1f%%', $item->progress),
//			'commit'      => $item->commit,
//			'logs'        => $this->jobLogRepository->hasLog($item->id),
//			'userId' => $item->user_id,
//			'running'     => $this->processService->isRunning($item->pid),
//		]);
	}
}
