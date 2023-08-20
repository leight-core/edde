<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

use Edde\Query\Schema\CursorSchema;

interface JobQuerySchema {
	function filter(): ?JobFilterSchema;

	function orderBy(): ?JobOrderBySchema;

	function cursor(): ?CursorSchema;
}
