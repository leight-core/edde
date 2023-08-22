<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job\Query;

use Edde\Query\Schema\FilterSchema;

interface JobFilterSchema extends FilterSchema {
	function status(): ?int;

	function service(): ?string;

	function services($array = true): ?string;
}
