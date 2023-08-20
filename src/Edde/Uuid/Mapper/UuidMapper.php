<?php
declare(strict_types=1);

namespace Edde\Uuid\Mapper;

use Edde\Mapper\AbstractMapper;
use Edde\Uuid\UuidServiceTrait;

class UuidMapper extends AbstractMapper {
	use UuidServiceTrait;

	public function item($item, $params = null) {
		return $this->uuidService->uuid4();
	}
}
