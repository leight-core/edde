<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Dto\Value;
use Edde\Mapper\AbstractMapper;

abstract class AbstractDtoMapper extends AbstractMapper {
	public function item($item, $params = null) {
		if (!is_array($params) || !array_key_exists('dto', $params) || !array_key_exists('value', $params)) {
			throw new SmartDtoException(sprintf('Cannot proxy value; $params is not an array with [dto, value] keys (defaults from SmartDto).'));
		} else if (!($params['value'] instanceof Value)) {
			throw new SmartDtoException(sprintf('Cannot proxy value; [value] in $params is not instance of [%s].', Value::class));
		} else if (!($params['dto'] instanceof SmartDto)) {
			throw new SmartDtoException(sprintf('Cannot proxy value; [dto] in $params is not instance of [%s].', SmartDto::class));
		}
		return $this->handle($params['value'], $params['dto']);
	}

	abstract protected function handle(Value $value, SmartDto $dto);
}
