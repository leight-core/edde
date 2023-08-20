<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Internal;

use Edde\Bulk\Schema\Bulk\Query\BulkFilterSchema;

interface BulkUpdateRequestSchema {
	function update($load = true): BulkUpdateSchema;

	function filter($load = true): BulkFilterSchema;
}
