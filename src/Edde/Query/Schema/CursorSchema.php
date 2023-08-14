<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

interface CursorSchema {
	function page(): int;

	function size(): int;
}
