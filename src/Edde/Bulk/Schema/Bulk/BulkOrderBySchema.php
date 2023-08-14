<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

interface BulkOrderBySchema {
	const meta = [
		'orderBy' => [
			'field',
			'field2',
		],
	];
}
