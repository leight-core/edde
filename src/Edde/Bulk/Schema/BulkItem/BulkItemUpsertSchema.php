<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

use Edde\Bulk\Schema\BulkItem\Query\BulkItemFilterSchema;

interface BulkItemUpsertSchema {
	const meta = [
		'import' => [
			'BulkItemUpsertSchema'       => '@leight/bulk',
			'type IBulkItemUpsertSchema' => '@leight/bulk',
			'type IBulkItemUpsert'       => '@leight/bulk',
		],
	];

	function create($load = true): ?BulkItemCreateSchema;

	function update($load = true): ?BulkItemCreateSchema;

	function filter($load = true): ?BulkItemFilterSchema;
}
