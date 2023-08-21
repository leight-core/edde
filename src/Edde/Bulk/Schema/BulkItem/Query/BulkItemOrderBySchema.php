<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Query;

interface BulkItemOrderBySchema {
	const meta = [
		'import' => [
			'type IBulkItemOrderBy'       => '@leight/bulk',
			'type IBulkItemOrderBySchema' => '@leight/bulk',
			'BulkItemOrderBySchema'       => '@leight/bulk',
		],
		'orderBy' => [
			'status',
		],
	];

	function status(): ?string;
}
