<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Query;

interface BulkOrderBySchema {
	const meta = [
		'import' => [
            'type IBulkOrderBy'       => '@pico/bulk',
            'type IBulkOrderBySchema' => '@pico/bulk',
            'BulkOrderBySchema'       => '@pico/bulk',
		],
		'orderBy' => [
			'created',
			'name',
		],
	];
}
