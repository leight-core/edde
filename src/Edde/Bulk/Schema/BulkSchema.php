<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema;

abstract class BulkSchema {
	const meta = [
		'import' => [
			'type IBulk'       => '@leight/bulk',
			'type IBulkSchema' => '@leight/bulk',
			'BulkSchema'       => '@leight/bulk',
		],
	];

	abstract function id(): string;

	abstract function name(): string;

	abstract function service(): string;

	abstract function status(): int;

	abstract function commit(): bool;

	abstract function created(): string;

	abstract function userId(): string;
}
