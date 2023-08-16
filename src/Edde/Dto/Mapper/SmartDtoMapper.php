<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use Edde\Dto\Exception\DtoException;
use Edde\Dto\SmartServiceTrait;
use Edde\Mapper\AbstractMapper;

class SmartDtoMapper extends AbstractMapper {
	use SmartServiceTrait;

	public function item($item, $params = null) {
		if (!$params) {
			throw new DtoException('Cannot map item to SmartDto - missing $param (schema name).');
		}
		return $this->smartService->from($item, $params);
	}
}
