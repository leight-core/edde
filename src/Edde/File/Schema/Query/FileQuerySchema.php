<?php
declare(strict_types=1);

namespace Edde\File\Schema\Query;

use Edde\Query\Schema\CursorSchema;

interface FileQuerySchema {
	function where($load = true): ?FileFilterSchema;

	function filter($load = true): ?FileFilterSchema;

	function orderBy($load = true): ?FileOrderBySchema;

	function cursor($load = true): ?CursorSchema;
}
