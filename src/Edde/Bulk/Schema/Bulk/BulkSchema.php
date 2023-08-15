<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

use Edde\Date\Mapper\IsoDateMapper;

interface BulkSchema {
	const meta = [
		'import' => [
			'type IBulk'       => '@leight/bulk',
			'type IBulkSchema' => '@leight/bulk',
			'BulkSchema'       => '@leight/bulk',
		],
	];

	function id(): string;

	function name(): string;

	function service(): string;

	function status(): int;

	function commit(): bool;

	function created($output = IsoDateMapper::class): string;

	function userId(): string;
}
