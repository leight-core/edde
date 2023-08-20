<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Internal;

use Edde\Date\Mapper\IsoDateMapper;
use Edde\Dto\Mapper\ExportMapper;
use Edde\Utils\Mapper\BoolIntMapper;

interface BulkCreateSchema {
	const meta = [
		ExportMapper::META => [
			'userId' => ExportMapper::CONVERT_CAMEL,
		],
	];

	function name(): string;

	function status(): int;

	function commit($output = BoolIntMapper::class): bool;

	function created($output = IsoDateMapper::class);

	function userId(): string;
}
