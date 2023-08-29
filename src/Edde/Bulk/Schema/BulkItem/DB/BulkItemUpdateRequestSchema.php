<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\DB;

use Edde\Bulk\Schema\BulkItem\Query\BulkItemFilterSchema;

interface BulkItemUpdateRequestSchema {
	function update($load = true): BulkItemUpdateSchema;

	function filter($load = true): BulkItemFilterSchema;
}
