<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Internal;

use Edde\Date\Mapper\IsoDateMapper;
use Edde\Dto\Mapper\ExportMapper;

interface BulkCreateSchema {
	const meta = [
		ExportMapper::META => [
			'userId' => ExportMapper::CONVERT_CAMEL,
		],
	];

	function name(): string;

	function status(): int;

	function commit(): bool;

	function created($output = IsoDateMapper::class): string;

	function userId(): string;
}
