<?php
declare(strict_types=1);

namespace Edde\Upgrade\Schema;

use Edde\Database\Schema\UuidSchema;

interface UpgradeSchema extends UuidSchema {
	function version(): string;

	function name(): string;

	function active(): bool;
}
