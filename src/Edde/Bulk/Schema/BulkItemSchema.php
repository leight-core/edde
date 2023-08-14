<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema;

interface BulkItemSchema {
	const meta = [
		'import' => [
			'type IBulkItem'       => '@leight/bulk',
			'type IBulkItemSchema' => '@leight/bulk',
			'BulkItemSchema'       => '@leight/bulk',
		],
	];

	function id(): string;

	function bulkId(): string;

	function bulk($load = true): BulkSchema;

	function status(): int;

	function request(): ?string;

	function response(): ?string;

	function userId(): string;
}
