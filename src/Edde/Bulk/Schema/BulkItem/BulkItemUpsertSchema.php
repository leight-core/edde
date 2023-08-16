<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

interface BulkItemUpsertSchema {
	function create($load = true): ?BulkItemCreateSchema;

	function update($load = true): ?BulkItemCreateSchema;

	function filter($load = true): BulkItemFilterSchema;
}
