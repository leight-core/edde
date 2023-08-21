<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Internal;

use Edde\Bulk\Schema\BulkItem\Query\BulkItemFilterSchema;

interface BulkItemUpsertSchema {
	function create($load = true): ?BulkItemCreateSchema;

	function update($load = true): ?BulkItemUpdateSchema;

	function filter($load = true): ?BulkItemFilterSchema;
}
