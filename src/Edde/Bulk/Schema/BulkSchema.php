<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema;

abstract class BulkSchema {
	public $meta = [
		'import' => [
			'BulkSchema' => '@leight/bulk',
		],
	];

	abstract function id(): string;

	abstract function created(): string;

	abstract function name(): string;

	abstract function status(): int;

	abstract function commit(): bool;

	abstract function userId(): string;
}
