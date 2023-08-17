<?php
declare(strict_types=1);

namespace Edde\Job\Schema\JobLock;

use Edde\Query\Schema\CursorSchema;

interface JobLockQuerySchema {
	function filter($load = true): ?JobLockFilterSchema;

	function orderBy($load = true): ?JobLockOrderBySchema;

	function cursor($load = true): ?CursorSchema;
}
