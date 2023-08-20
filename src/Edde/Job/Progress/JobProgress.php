<?php
declare(strict_types=1);

namespace Edde\Job\Progress;

use DateTime;
use Edde\Doctrine\EntityManagerTrait;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Job\Exception\JobInterruptedException;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Job\Schema\Job\JobPatchRequestSchema;
use Edde\Job\Schema\JobStatus;
use Edde\Job\Service\JobLogServiceTrait;
use Edde\Job\Service\JobServiceTrait;
use Edde\Log\LoggerTrait;
use Edde\Mapper\Exception\ItemException;
use Edde\Progress\AbstractProgress;
use Edde\Progress\IProgress;
use Throwable;

class JobProgress extends AbstractProgress {
	use JobServiceTrait;
	use SmartServiceTrait;
	use JobRepositoryTrait;
	use JobLogServiceTrait;
	use EntityManagerTrait;
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
		$this->jobService->patch(
			$this->smartService->from(
				[
					'patch'  => [
						'started' => new DateTime(),
						'total'   => $this->total = $total,
						'status'  => JobStatus::JOB_RUNNING,
					],
					'filter' => [
						'id' => $this->jobId,
					],
				],
				JobPatchRequestSchema::class
			)
		);
		$this->check();
		$this->entityManager->flush();
	}

	/**
	 * @inheritdoc
	 */
	public function onProgress(): void {
		$this->check();
		$this->jobService->patch(
			$this->smartService->from(
				[
					'patch'  => [
						'successCount' => ++$this->success,
						'progress'     => $this->progress(),
					],
					'filter' => [
						'id' => $this->jobId,
					],
				],
				JobPatchRequestSchema::class
			)
		);
		$this->entityManager->flush();
	}

	public function onSettled(SmartDto $response = null): void {
		$job = $this->jobService->find($this->jobId);
		$this->jobService->patch(
			$this->smartService->from(
				[
					'patch'  => [
						'status'         => $job->getSafeValue('errorCount', 0) > 0 ? JobStatus::JOB_CHECK : JobStatus::JOB_SUCCESS,
						'response'       => $response ? $response->export() : null,
						'responseSchema' => $response ? $response->getSchema()->getName() : null,
						'progress'       => 100,
						'finished'       => new DateTime(),
					],
					'filter' => [
						'id' => $this->jobId,
					],
				],
				JobPatchRequestSchema::class
			)
		);
		$this->entityManager->flush();
	}

	/**
	 * @inheritdoc
	 */
	public function onError(Throwable $throwable, string $reference = null): void {
		parent::onError($throwable, $reference);
		$this->check();
		$this->jobService->patch(
			$this->smartService->from(
				[
					'patch'  => [
						'errorCount' => $this->error,
						'progress'   => $this->progress(),
					],
					'filter' => [
						'id' => $this->jobId,
					],
				],
				JobPatchRequestSchema::class
			)
		);
		try {
			throw $throwable;
		} catch (ItemException $itemException) {
			$type = $itemException->getType();
			$this->context['exception'] = $itemException->getExtra();
		} catch (Throwable $throwable) {
			$type = null;
		}
		$this->logger->error($throwable);
		$this->jobLogService->log(
			$this->jobId,
			IProgress::LOG_ERROR,
			$throwable->getMessage(),
			$this->context,
			$type,
			$reference
		);
		$this->entityManager->flush();
	}

	public function onFailure(Throwable $throwable): void {
		$this->jobService->patch(
			$this->smartService->from(
				[
					'patch'  => [
						'status' => JobStatus::JOB_ERROR,
					],
					'filter' => [
						'id' => $this->jobId,
					],
				],
				JobPatchRequestSchema::class
			)
		);
		$this->logger->error($throwable);
		$this->log(self::LOG_ERROR, $throwable->getMessage(), null, 'job.failure');
		$this->entityManager->flush();
	}

	public function check(): void {
		parent::check();
		$job = $this->jobService->find($this->jobId);
		if ($job->getValue('status') === JobStatus::JOB_INTERRUPTED) {
			$this->jobService->patch(
				$this->smartService->from(
					[
						'patch'  => [
							'finished' => new DateTime(),
						],
						'filter' => [
							'id' => $this->jobId,
						],
					],
					JobPatchRequestSchema::class
				)
			);
			throw new JobInterruptedException(sprintf('Job [%s] has been interrupted.', $this->jobId));
		}
	}

	public function log(int $level, string $message, string $type = null, string $reference = null) {
		$this->jobLogService->log(
			$this->jobId,
			$level,
			$message,
			$this->context,
			$type,
			$reference
		);
	}
}
