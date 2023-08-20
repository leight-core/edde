<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Query;

use Edde\Query\Schema\CursorSchema;

interface BulkQuerySchema {
	function filter(): ?BulkFilterSchema;

	function orderBy(): ?BulkOrderBySchema;

	function cursor(): ?CursorSchema;
}
