<?php
declare(strict_types=1);

namespace Edde\Job\Schema\JobLock;

use DateTime;
use Edde\Database\Schema\UuidSchema;

interface JobLockSchema extends UuidSchema {
	function jobId(): string;

	function name(): string;

	function stamp(): DateTime;

	function active(): bool;
}
