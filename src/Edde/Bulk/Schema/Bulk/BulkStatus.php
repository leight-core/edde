<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

interface BulkStatus {
	const PENDING = 0;
	const RUNNING = 1;
	const SUCCESS = 2;
	const ERROR = 3;
	const SETTLED = 4;
}
