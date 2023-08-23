<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

interface BulkCreateSchema {
	const meta = [
		'import' => [
            'type IBulkCreate'       => '@pico/bulk',
            'type IBulkCreateSchema' => '@pico/bulk',
            'BulkCreateSchema'       => '@pico/bulk',
		],
	];

	function name(): string;
}
