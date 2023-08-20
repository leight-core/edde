<?php
declare(strict_types=1);

namespace Edde\Upgrade\Schema;

use Edde\Query\Schema\CursorSchema;

interface UpgradeQuerySchema {
	function filter(): ?UpgradeFilterSchema;

	function orderBy(): ?UpgradeOrderBySchema;

	function cursor(): ?CursorSchema;
}
