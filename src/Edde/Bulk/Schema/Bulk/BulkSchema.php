<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

use Edde\Date\Mapper\IsoDateMapper;
use Edde\Doctrine\Schema\UuidSchema;

interface BulkSchema extends UuidSchema {
	const meta = [
		'import' => [
			'type IBulk'       => '@leight/bulk',
			'type IBulkSchema' => '@leight/bulk',
			'BulkSchema'       => '@leight/bulk',
		],
	];

	function name(): string;

	function service(): string;

	function status(): int;

	function commit(): bool;

	function created($output = IsoDateMapper::class): string;

	function userId(): string;
}
