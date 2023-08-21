<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

interface BulkStatus {
	const PENDING = 0;
	const RUNNING = 1;
	const SETTLED = 2;
}
