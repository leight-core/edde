<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

abstract class QuerySchema {
	abstract public function cursor($load = true): ?CursorSchema;

	abstract public function filter($type = FilterSchema::class, $load = true);
}
