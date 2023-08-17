<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

use DateTime;

interface JobCreateSchema {
	function name(): string;

	function started(): DateTime;

	function params(): ?string;

	function schema(): string;
}
