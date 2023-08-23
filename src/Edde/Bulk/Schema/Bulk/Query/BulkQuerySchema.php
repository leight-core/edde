<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Query;

use Edde\Query\Schema\CursorSchema;

interface BulkQuerySchema {
	const meta = [
		'import' => [
            'BulkQuerySchema'       => '@pico/bulk',
            'type IBulkQuerySchema' => '@pico/bulk',
            'type IBulkQuery'       => '@pico/bulk',
		],
	];

	function filter(): ?BulkFilterSchema;

	function orderBy(): ?BulkOrderBySchema;

	function cursor(): ?CursorSchema;
}
