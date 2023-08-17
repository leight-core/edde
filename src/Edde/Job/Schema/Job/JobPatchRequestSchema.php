<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

interface JobPatchRequestSchema {
	function patch($load = true): JobPatchSchema;

	function filter($load = true): JobFilterSchema;
}
