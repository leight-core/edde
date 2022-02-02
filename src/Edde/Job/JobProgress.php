<?php
declare(strict_types=1);

namespace Edde\Job;

use DateTime;
use Edde\Job\Exception\JobInterruptedException;
use Edde\Job\Repository\JobLogRepositoryTrait;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Log\LoggerTrait;
use Edde\Mapper\Exception\ItemException;
use Edde\Progress\AbstractProgress;
use Edde\Progress\IProgress;
use Throwable;

class JobProgress extends AbstractProgress {
	use JobRepositoryTrait;
	use JobLogRepositoryTrait;
	use LoggerTrait;

	/** @var string */
	protected $jobId;
	/** @var float */
	protected $start;

	public function __construct(string $jobId) {
		$this->jobId = $jobId;
	}

	public function onStart(int $total = 1): void {
		$this->start = microtime(true);
		$this->check();
		$this->jobRepository->change([
			'id'     => $this->jobId,
			'total'  => $this->total = $total,
			'status' => JobStatus::JOB_RUNNING,
		]);
		$this->check();
	}

	/**
	 * @inheritdoc
	 */
	public function onProgress(): void {
		$this->check();
		$this->jobRepository->change([
			'id'       => $this->jobId,
			'success'  => ++$this->success,
			'runtime'  => microtime(true) - $this->start,
			'progress' => $this->progress(),
		]);
	}

	public function onDone($result): void {
		$job = $this->jobRepository->find($this->jobId);
		$this->jobRepository->change([
			'id'          => $this->jobId,
			/**
			 * If there are some errors, one has to do a check jobs.
			 */
			'status'      => ($job->error > 0 || $this->jobLogRepository->hasLog($job->id)) ? JobStatus::JOB_CHECK : JobStatus::JOB_DONE,
			'done'        => new DateTime(),
			'result'      => json_encode($result),
			'runtime'     => ($runtime = microtime(true) - $this->start),
			'performance' => $runtime / max($job->success + $job->error, 1),
			'progress'    => 100,
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function onError(Throwable $throwable, string $reference = null): void {
		parent::onError($throwable, $reference);
		$this->check();
		$this->jobRepository->change([
			'id'       => $this->jobId,
			'error'    => $this->error,
			'progress' => $this->progress(),
		]);
		try {
			throw $throwable;
		} catch (ItemException $itemException) {
			$type = $itemException->getType();
			$this->context['exception'] = $itemException->getExtra();
		} catch (Throwable $throwable) {
			$type = null;
		}
		$this->logger->error($throwable);
		$this->jobLogRepository->log($this->jobId, IProgress::LOG_ERROR, $throwable->getMessage(), $this->context, $type, $reference);
	}

	public function onFailure(Throwable $throwable): void {
		$this->jobRepository->change([
			'id'     => $this->jobId,
			'status' => JobStatus::JOB_FAILURE,
		]);
		$this->logger->error($throwable);
		$this->log(self::LOG_ERROR, $throwable->getMessage(), null, 'job.failure');
	}

	public function check(): void {
		parent::check();
		$job = $this->jobRepository->find($this->jobId);
		if ($job->status === JobStatus::JOB_INTERRUPTED) {
			$this->jobRepository->update($this->jobId, ['done' => new DateTime()]);
			throw new JobInterruptedException(sprintf('Job [%s] has been interrupted.', $this->jobId));
		}
	}

	public function log(int $level, string $message, string $type = null, string $reference = null) {
		$this->jobLogRepository->log(
			$this->jobId,
			$level,
			$message,
			$this->context,
			$type,
			$reference
		);
	}
}
