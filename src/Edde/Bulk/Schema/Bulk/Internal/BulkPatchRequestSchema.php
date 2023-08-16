<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Internal;

use Edde\Bulk\Schema\Bulk\BulkFilterSchema;

interface BulkPatchRequestSchema {
	function patch($load = true): BulkPatchSchema;

	function filter($load = true): BulkFilterSchema;
}
