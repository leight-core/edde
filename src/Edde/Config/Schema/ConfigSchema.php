<?php
declare(strict_types=1);

namespace Edde\Config\Schema;

use Edde\Database\Schema\UuidSchema;

interface ConfigSchema extends UuidSchema {
	function key(): string;

	function value();

	function private(): bool;
}
