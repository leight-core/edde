<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use Edde\Dto\Exception\SmartDtoException;
use ReflectionException;

interface IJobLogService {
	/**
	 * @param string      $jobId
	 * @param int         $level
	 * @param string      $message
	 * @param             $context
	 * @param string|null $type
	 * @param string|null $reference
	 *
	 * @return void
	 * @throws ReflectionException
	 * @throws SmartDtoException
	 */
	public function log(string $jobId, int $level, string $message, $context = null, string $type = null, string $reference = null): void;
}
