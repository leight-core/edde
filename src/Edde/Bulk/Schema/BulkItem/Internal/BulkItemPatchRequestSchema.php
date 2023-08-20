<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Internal;

use Edde\Bulk\Schema\BulkItem\BulkItemFilterSchema;

interface BulkItemPatchRequestSchema {
	function patch($load = true): BulkItemPatchSchema;

	function filter($load = true): BulkItemFilterSchema;
}
