<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Internal;

interface BulkUpdateSchema extends BulkCreateSchema {
	const partial = true;
}
