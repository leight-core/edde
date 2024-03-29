<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\DB;

use Edde\Database\Schema\UuidGeneratorSchema;
use Edde\Date\Mapper\IsoDateMapper;
use Edde\Dto\Mapper\ExportMapper;
use Edde\Utils\Mapper\BoolIntMapper;

interface BulkCreateSchema extends UuidGeneratorSchema {
	const meta = [
		ExportMapper::META => [
			'userId' => ExportMapper::CONVERT_SNAKE,
		],
	];

	function name(): string;

	function status(): int;

	function commit($output = BoolIntMapper::class): bool;

	function created($output = IsoDateMapper::class);

	function userId(): string;
}
