<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

interface BulkItemUpsertSchema {
	function create(): ?BulkItemCreateSchema;

	function update(): ?BulkItemCreateSchema;

	function filter(): BulkItemFilterSchema;
}
