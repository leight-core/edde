<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

interface QuerySchema {
	function cursor($load = true): ?CursorSchema;

	function filter($type = FilterSchema::class, $load = true);
}
