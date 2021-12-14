<?php
declare(strict_types=1);

namespace Edde\Job;

use Edde\Progress\IProgress;

interface IJob {
	/**
	 * Returns job id.
	 *
	 * @return string
	 */
	public function getId(): string;

	/**
	 * Return params for the job.
	 *
	 * @return mixed
	 */
	public function getParams();

	/**
	 * Return progress object for the job.
	 *
	 * @return IProgress
	 */
	public function getProgress(): IProgress;

	/**
	 * Return job's user id if available
	 *
	 * @return string|null
	 */
	public function getUserId(): ?string;
}
