<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

use Edde\Query\Schema\FilterSchema;

interface BulkFilterSchema extends FilterSchema {
	function withCommit(): bool;
}
