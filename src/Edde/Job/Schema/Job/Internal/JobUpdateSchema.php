<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job\Internal;

interface JobUpdateSchema extends JobCreateSchema {
	const partial = true;
}
