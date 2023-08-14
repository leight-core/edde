<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema;

abstract class BulkCreateSchema {
	abstract function name(): string;
}
