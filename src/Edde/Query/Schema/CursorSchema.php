<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

abstract class CursorSchema {
	abstract public function page(): int;

	abstract public function size(): int;
}
