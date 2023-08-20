<?php
declare(strict_types=1);

namespace Edde\Bulk\Mapper;

use Edde\Bulk\Schema\BulkItem\BulkItemSchema;
use Edde\Dto\Mapper\AbstractPushOfMapper;

class BulkItemDtoMapper extends AbstractPushOfMapper {
	public function getSchema(): string {
		return BulkItemSchema::class;
	}
}
