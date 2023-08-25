<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Query;

use Edde\Query\Schema\FilterSchema;

interface BulkFilterSchema extends FilterSchema {
	const meta = [
		'import' => [
            'type IBulkFilter'       => '@pico/bulk',
            'type IBulkFilterSchema' => '@pico/bulk',
            'BulkFilterSchema'       => '@pico/bulk',
		],
	];

    function userId(): ?string;

	function withCommit(): ?bool;
}
