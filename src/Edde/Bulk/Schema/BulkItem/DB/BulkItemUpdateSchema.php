<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\DB;

use Edde\Utils\Mapper\JsonInputMapper;

interface BulkItemUpdateSchema extends BulkItemCreateSchema {
	const partial = true;

	function response(
		$input = JsonInputMapper::class
	): string;
}
