<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

interface BulkFilterSchema {
	function withCommit(): bool;
}
