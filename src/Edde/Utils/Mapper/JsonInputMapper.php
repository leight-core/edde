<?php
declare(strict_types=1);

namespace Edde\Utils\Mapper;

use Edde\Mapper\AbstractMapper;
use Nette\Utils\Json;

class JsonInputMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return Json::encode($item);
	}
}
