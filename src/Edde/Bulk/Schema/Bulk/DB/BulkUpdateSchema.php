<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\DB;

use Edde\Date\Mapper\IsoDateMapper;
use Edde\Utils\Mapper\BoolIntMapper;

interface BulkUpdateSchema {
	function name(): string;

	function status(): int;

	function commit($output = BoolIntMapper::class): bool;

	function created($output = IsoDateMapper::class);

	function userId(): string;
}
