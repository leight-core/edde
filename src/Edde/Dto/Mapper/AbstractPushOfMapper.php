<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use Edde\Mapper\AbstractMapper;

abstract class AbstractPushOfMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return $this->smartService->pushOf($item, $this->getSchema());
	}

	abstract public function getSchema(): string;
}
