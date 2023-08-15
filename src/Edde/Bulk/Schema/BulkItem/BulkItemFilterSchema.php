<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

use Edde\Query\Schema\FilterSchema;

interface BulkItemFilterSchema extends FilterSchema {
	const meta = [
		'import' => [
			'type IBulkItemFilter'       => '@leight/bulk',
			'type IBulkItemFilterSchema' => '@leight/bulk',
			'BulkItemFilterSchema'       => '@leight/bulk',
		],
	];

	function bulkId(): ?string;
}
