<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Query;

use Edde\Query\Schema\FilterSchema;

interface BulkItemFilterSchema extends FilterSchema {
	const meta = [
		'import' => [
            'type IBulkItemFilter'       => '@pico/bulk',
            'type IBulkItemFilterSchema' => '@pico/bulk',
            'BulkItemFilterSchema'       => '@pico/bulk',
		],
	];

	function bulkId(): ?string;

	function service(): ?string;
}
