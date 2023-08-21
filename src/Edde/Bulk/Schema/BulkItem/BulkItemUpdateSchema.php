<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

interface BulkItemUpdateSchema extends BulkItemCreateSchema {
	const partial = true;
}
