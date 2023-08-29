<?php
declare(strict_types=1);

namespace Edde\File\Schema\DB;

use Edde\File\Schema\Query\FileFilterSchema;

interface FileUpsertSchema {
	function create($load = true): ?FileCreateSchema;

	function update($load = true): ?FileUpdateSchema;

	function filter($load = true): ?FileFilterSchema;
}
