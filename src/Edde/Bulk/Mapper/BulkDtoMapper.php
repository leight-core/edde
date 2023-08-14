<?php
declare(strict_types=1);

namespace Edde\Bulk\Mapper;

use Edde\Bulk\Schema\BulkSchema;
use Edde\Mapper\AbstractMapper;

class BulkDtoMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return $this->smartService->from($item, BulkSchema::class);
	}
}
