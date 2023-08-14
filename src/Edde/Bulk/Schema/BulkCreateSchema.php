<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema;

abstract class BulkCreateSchema {
	public $meta = [
		'import' => [
			'BulkCreateSchema' => '@leight/bulk',
		],
	];

	abstract function name(): string;
}
