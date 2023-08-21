<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Query;

use Edde\Dto\Mapper\ExportMapper;
use Edde\Query\Schema\FilterSchema;

interface BulkItemFilterSchema extends FilterSchema {
	const meta = [
		'import'           => [
			'type IBulkItemFilter'       => '@leight/bulk',
			'type IBulkItemFilterSchema' => '@leight/bulk',
			'BulkItemFilterSchema'       => '@leight/bulk',
		],
		ExportMapper::META => [
			'bulkId' => ExportMapper::CONVERT_SNAKE,
		],
	];

	function bulkId(): ?string;

	function service(): ?string;
}
