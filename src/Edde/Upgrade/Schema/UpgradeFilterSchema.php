<?php
declare(strict_types=1);

namespace Edde\Upgrade\Schema;

use Edde\Query\Schema\FilterSchema;

interface UpgradeFilterSchema extends FilterSchema {
	function active(): ?bool;
}
