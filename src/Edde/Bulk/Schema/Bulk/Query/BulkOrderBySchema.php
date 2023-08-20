<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Query;

interface BulkOrderBySchema {
	const meta = [
		'import' => [
			'type IBulkOrderBy'       => '@leight/bulk',
			'type IBulkOrderBySchema' => '@leight/bulk',
			'BulkOrderBySchema'       => '@leight/bulk',
		],
		'orderBy' => [
			'created',
			'name',
		],
	];
}
