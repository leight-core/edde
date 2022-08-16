<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use ClanCats\Hydrahon\Query\Sql\SelectBase;
use DateTime;
use Dibi\Exception;
use Edde\Job\Dto\Commit\CommitDto;
use Edde\Job\Dto\Delete\DeleteDto;
use Edde\Job\Dto\Interrupt\InterruptDto;
use Edde\Job\Dto\JobFilterDto;
use Edde\Job\IJobService;
use Edde\Job\JobStatus;
use Edde\Repository\AbstractRepository;
use Edde\Repository\Dto\AbstractFilterDto;
use Edde\Repository\IRepository;
use Throwable;

class JobRepository extends AbstractRepository {
	use JobLogRepositoryTrait;

	public function __construct() {
		parent::__construct(['created' => IRepository::ORDER_DESC]);
		$this->fulltext = [
			'id',
			'service',
			'user_id',
			'params',
		];
	}

	public function applyWhere(?AbstractFilterDto $filterDto, SelectBase $selectBase): void {
		/** @var $filterDto JobFilterDto */
		parent::applyWhere($filterDto, $selectBase);
		isset($filterDto->userId) && $selectBase->where('user_id', $filterDto->userId);
		isset($filterDto->services) && $selectBase->where('service', 'in', $filterDto->services);
		isset($filterDto->id) && $selectBase->where('id', $filterDto->id);
		isset($filterDto->status) && $selectBase->where('status', 'in', $filterDto->status);
		isset($filterDto->commit) && $selectBase->where('commit', $filterDto->commit);
		isset($filterDto->params) && $this->fulltext($selectBase, [
			'params',
		], $filterDto->params);
	}

	/**
	 * @param IJobService $jobService
	 * @param string|null $userId
	 * @param mixed|null  $params
	 *
	 * @return mixed
	 *
	 * @throws Throwable
	 * @throws Exception
	 */
	public function create(IJobService $jobService, ?string $userId, $params = null) {
		return $this->insert([
			/**
			 * Because there is no job scheduler, all jobs will be executed at the time of creation (or when
			 * job executor gets it).
			 */
			'status'  => JobStatus::JOB_SCHEDULED,
			'service' => get_class($jobService),
			'params'  => json_encode($params),
			'created' => new DateTime(),
			'user_id' => $userId,
		]);
	}

	public function update(string $jobId, array $update) {
		$update['id'] = $jobId;
		return $this->change($update);
	}

	public function updateFailure(string $jobId, Throwable $throwable) {
		$this->change([
			'id'     => $jobId,
			'status' => JobStatus::JOB_FAILURE,
		]);
	}

	public function cleanup(): void {
		$this->native("DELETE FROM %n WHERE status = ? OR commit = true", $this->table, JobStatus::JOB_DONE);
	}

	public function commitBy(?CommitDto $commitDto) {
		$update = $this
			->table()
			->update(['commit' => true])
			->where('status', 'in', [
				JobStatus::JOB_FAILURE,
				JobStatus::JOB_CHECK,
				JobStatus::JOB_DONE,
				JobStatus::JOB_INTERRUPTED,
			]);

		if ($commitDto) {
			$commitDto->userId && $update->where('user_id', $commitDto->userId);
			$commitDto->jobId && $update->where('id', $commitDto->jobId);
		}

		$update->execute();
	}

	public function interruptBy(?InterruptDto $interruptDto) {
		$update = $this
			->table()
			->update(['status' => JobStatus::JOB_INTERRUPTED])
			->where('status', 'in', [
				JobStatus::JOB_CREATED,
				JobStatus::JOB_SCHEDULED,
				JobStatus::JOB_RUNNING,
			]);

		if ($interruptDto) {
			$interruptDto->userId && $update->where('user_id', $interruptDto->userId);
			$interruptDto->jobId && $update->where('id', $interruptDto->jobId);
		}

		$update->execute();
	}

	public function deleteBy(?DeleteDto $deleteDto) {
		$update = $this
			->table()
			->delete()
			->where('status', 'in', [
				JobStatus::JOB_FAILURE,
				JobStatus::JOB_CHECK,
				JobStatus::JOB_DONE,
				JobStatus::JOB_INTERRUPTED,
				JobStatus::JOB_SCHEDULED,
				JobStatus::JOB_CREATED,
			]);

		if ($deleteDto) {
			$deleteDto->userId && $update->where('user_id', $deleteDto->userId);
			$deleteDto->jobId && $update->where('id', $deleteDto->jobId);
			$deleteDto->services && $update->where('service', 'in', $deleteDto->services);
		}

		$update->execute();
	}
}
