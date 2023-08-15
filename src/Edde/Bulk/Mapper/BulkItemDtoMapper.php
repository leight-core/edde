<?php
declare(strict_types=1);

namespace Edde\Bulk\Mapper;

use Edde\Bulk\Schema\BulkItem\BulkItemSchema;
use Edde\Mapper\AbstractMapper;

class BulkItemDtoMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return $this->smartService->from($item, BulkItemSchema::class);
	}
}
