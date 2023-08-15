<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

interface BulkItemCreateSchema {
	const meta = [
		'import' => [
			'type IBulkItemCreate'       => '@leight/bulk',
			'type IBulkItemCreateSchema' => '@leight/bulk',
			'BulkItemCreateSchema'       => '@leight/bulk',
		],
	];
}
