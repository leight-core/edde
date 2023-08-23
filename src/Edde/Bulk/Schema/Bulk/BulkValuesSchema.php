<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

interface BulkValuesSchema {
	const meta = [
		'import' => [
            'type IBulkValuesSchema' => '@pico/bulk',
            'type IBulkValues'       => '@pico/bulk',
            'BulkValuesSchema'       => '@pico/bulk',
		],
	];

	function name(): string;
}
