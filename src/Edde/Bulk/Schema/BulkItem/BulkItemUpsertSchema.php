<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

use Edde\Bulk\Schema\BulkItem\Query\BulkItemFilterSchema;

interface BulkItemUpsertSchema {
	const meta = [
		'import' => [
            'BulkItemUpsertSchema'       => '@pico/bulk',
            'type IBulkItemUpsertSchema' => '@pico/bulk',
            'type IBulkItemUpsert'       => '@pico/bulk',
		],
	];

	function create($load = true): ?BulkItemCreateSchema;

	function update($load = true): ?BulkItemUpdateSchema;

	function filter($load = true): ?BulkItemFilterSchema;
}
