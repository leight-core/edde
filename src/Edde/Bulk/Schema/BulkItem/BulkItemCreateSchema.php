<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

use Edde\Utils\Mapper\JsonInputMapper;
use Edde\Utils\Mapper\JsonOutputMapper;

interface BulkItemCreateSchema {
	const meta = [
		'import' => [
			'type IBulkItemCreate'       => '@leight/bulk',
			'type IBulkItemCreateSchema' => '@leight/bulk',
			'BulkItemCreateSchema'       => '@leight/bulk',
		],
	];

	function bulkId(): string;

	function service(): string;

	function request(
		$input = JsonInputMapper::class,
		$output = JsonOutputMapper::class
	);
}
