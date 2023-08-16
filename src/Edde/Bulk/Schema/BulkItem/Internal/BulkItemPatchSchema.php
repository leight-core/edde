<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Internal;

interface BulkItemPatchSchema extends BulkItemCreateSchema {
	const partial = true;
}
