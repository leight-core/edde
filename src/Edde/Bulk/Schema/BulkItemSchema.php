<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema;

abstract class BulkItemSchema {
	public $meta = [
		'import' => [
			'BulkItemSchema' => '@leight/bulk',
		],
	];

	abstract function id(): string;

	abstract function bulkId(): string;

	abstract function bulk($load = true): BulkSchema;

	abstract function status(): int;

	abstract function request(): ?string;

	abstract function response(): ?string;

	abstract function userId(): string;
}
