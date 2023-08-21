<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

use Edde\Dto\Mapper\ITypeMapper;

interface BulkItemUpdateSchema extends BulkItemCreateSchema {
	const partial = true;

	function status(): ?int;

	function response(
		$type = ITypeMapper::TYPE_JSON
	): string;
}
