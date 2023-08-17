<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

interface JobPatchSchema extends JobCreateSchema {
	const partial = true;
}
