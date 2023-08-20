<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

interface BulkValuesSchema {
	const meta = [
		'import' => [
			'type IBulkValuesSchema' => '@leight/bulk',
			'type IBulkValues'       => '@leight/bulk',
			'BulkValuesSchema'       => '@leight/bulk',
		],
	];

	function name(): string;
}
