<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

interface BulkItemStatus {
	const PENDING = 0;
	const SUCCESS = 1;
	const ERROR = 2;
	const SETTLED = 3;
}
