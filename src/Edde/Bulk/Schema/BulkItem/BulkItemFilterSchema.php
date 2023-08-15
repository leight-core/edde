<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

use Edde\Query\Schema\FilterSchema;

interface BulkItemFilterSchema extends FilterSchema {
	function bulkId(): ?string;
}
