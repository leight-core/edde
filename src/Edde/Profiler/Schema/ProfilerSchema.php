<?php
declare(strict_types=1);

namespace Edde\Profiler\Schema;

use Edde\Database\Schema\UuidSchema;

interface ProfilerSchema extends UuidSchema {
	function name(): string;

	function stamp(): float;

	function runtime(): float;
}
