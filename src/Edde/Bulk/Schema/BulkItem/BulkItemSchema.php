<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Date\Mapper\IsoDateMapper;
use Edde\Doctrine\Schema\UuidSchema;

interface BulkItemSchema extends UuidSchema {
	const meta = [
		'import' => [
			'type IBulkItem'       => '@leight/bulk',
			'type IBulkItemSchema' => '@leight/bulk',
			'BulkItemSchema'       => '@leight/bulk',
		],
	];

	function bulkId(): string;

	function bulk($load = true): BulkSchema;

	function created($output = IsoDateMapper::class): string;

	function status(): int;

	function request(): ?string;

	function response(): ?string;

	function userId(): string;
}
