<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema;

abstract class BulkCreateSchema {
	const meta = [
		'import' => [
			'type IBulkCreate'       => '@leight/bulk',
			'type IBulkCreateSchema' => '@leight/bulk',
			'BulkCreateSchema'       => '@leight/bulk',
		],
	];

	abstract function name(): string;

	abstract function service(): string;
}
