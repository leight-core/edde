<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema;

interface BulkCreateSchema {
	const meta = [
		'import' => [
			'type IBulkCreate'       => '@leight/bulk',
			'type IBulkCreateSchema' => '@leight/bulk',
			'BulkCreateSchema'       => '@leight/bulk',
		],
	];

	function name(): string;

	function service(): string;
}
