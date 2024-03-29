<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\DB;

use Edde\Database\Schema\UuidGeneratorSchema;
use Edde\Dto\Mapper\ExportMapper;
use Edde\Dto\Mapper\OutputTypeMapper;
use Edde\Utils\Mapper\JsonInputMapper;

interface BulkItemCreateSchema extends UuidGeneratorSchema {
	const meta = [
		ExportMapper::META => [
			'bulkId' => ExportMapper::CONVERT_SNAKE,
			'userId' => ExportMapper::CONVERT_SNAKE,
		],
	];

	function bulkId(): string;

	function service(): string;

	function request(
		$input = JsonInputMapper::class
	): string;

	function created($type = OutputTypeMapper::TYPE_ISO_DATETIME);

	function status(): int;

	function userId(): string;
}
