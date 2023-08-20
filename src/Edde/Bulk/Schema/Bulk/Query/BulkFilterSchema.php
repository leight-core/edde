<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Query;

use Edde\Query\Schema\FilterSchema;

interface BulkFilterSchema extends FilterSchema {
	const meta = [
		'import' => [
			'type IBulkFilter'       => '@leight/bulk',
			'type IBulkFilterSchema' => '@leight/bulk',
			'BulkFilterSchema'       => '@leight/bulk',
		],
	];

	function withCommit(): ?bool;
}
