<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

use Edde\Query\Schema\CursorSchema;

interface JobQuerySchema {
	function filter($load = true): ?JobFilterSchema;

	function orderBy($load = true): ?JobOrderBySchema;

	function cursor($load = true): ?CursorSchema;
}
