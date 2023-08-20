<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Internal;

interface BulkItemUpdateSchema extends BulkItemCreateSchema {
	const partial = true;
}
