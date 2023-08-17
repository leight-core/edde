<?php
declare(strict_types=1);

namespace Edde\Job\Schema\JobLock;

use Edde\Query\Schema\FilterSchema;

interface JobLockFilterSchema extends FilterSchema {
	function name(): ?string;

	function active(): ?bool;
}
