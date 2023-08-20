<?php
declare(strict_types=1);

namespace Edde\Bulk\Mapper;

use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Dto\Mapper\AbstractPushOfMapper;

class BulkDtoMapper extends AbstractPushOfMapper {
	public function getSchema(): string {
		return BulkSchema::class;
	}
}
