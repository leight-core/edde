<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Query;

interface BulkItemOrderBySchema {
	const meta = [
		'import'  => [
            'type IBulkItemOrderBy'       => '@pico/bulk',
            'type IBulkItemOrderBySchema' => '@pico/bulk',
            'BulkItemOrderBySchema'       => '@pico/bulk',
		],
		'orderBy' => [
			'status',
			'created',
		],
	];

	function status(): ?string;

	function created(): ?string;
}
