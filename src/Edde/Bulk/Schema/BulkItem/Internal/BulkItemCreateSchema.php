<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Internal;

use DateTime;
use Edde\Dto\Mapper\ExportMapper;
use Edde\Utils\Mapper\JsonInputMapper;
use Edde\Utils\Mapper\JsonOutputMapper;

interface BulkItemCreateSchema {
	const meta = [
		ExportMapper::META => [
			'bulkId' => ExportMapper::CONVERT_CAMEL,
			'userId' => ExportMapper::CONVERT_CAMEL,
		],
	];

	function bulkId(): string;

	function service(): string;

	function request(
		$input = JsonInputMapper::class,
		$output = JsonOutputMapper::class
	);

	function created(): DateTime;

	function status(): int;

	function userId(): string;
}
