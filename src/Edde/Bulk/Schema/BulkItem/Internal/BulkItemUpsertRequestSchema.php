<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Internal;

use Edde\Bulk\Schema\BulkItem\BulkItemFilterSchema;

interface BulkItemUpsertRequestSchema {
	function create($load = true): ?BulkItemCreateSchema;

	function update($load = true): ?BulkItemPatchSchema;

	function filter($load = true): ?BulkItemFilterSchema;
}
