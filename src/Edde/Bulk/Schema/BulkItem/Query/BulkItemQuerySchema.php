<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Query;

use Edde\Query\Schema\CursorSchema;

interface BulkItemQuerySchema {
	const meta = [
		'import' => [
			'type IBulkItemQuery'       => '@leight/bulk',
			'type IBulkItemQuerySchema' => '@leight/bulk',
			'BulkItemQuerySchema'       => '@leight/bulk',
		],
	];

	function filter($load = true): ?BulkItemFilterSchema;

	function orderBy($load = true): ?BulkItemOrderBySchema;

	function cursor($load = true): ?CursorSchema;
}
