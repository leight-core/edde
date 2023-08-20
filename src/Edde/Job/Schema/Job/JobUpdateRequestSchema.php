<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

use Edde\Job\Schema\Job\Query\JobFilterSchema;

interface JobUpdateRequestSchema {
	function update($load = true): JobUpdateSchema;

	function filter($load = true): JobFilterSchema;
}
