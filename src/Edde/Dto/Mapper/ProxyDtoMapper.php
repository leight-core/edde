<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use Edde\Container\ContainerTrait;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Dto\Value;
use Edde\Mapper\AbstractMapper;

class ProxyDtoMapper extends AbstractMapper {
	use ContainerTrait;

	public function item($item, $params = null) {
		if (!is_array($params) || !in_array('dto', $params) || !in_array('value', $params)) {
			throw new SmartDtoException(sprintf('Cannot proxy value; $params is not an array with [dto, value] keys (defaults from SmartDto).'));
		} else if (!($params['value'] instanceof Value)) {
			throw new SmartDtoException(sprintf('Cannot proxy value; [value] in $params is not instance of [%s].', Value::class));
		} else if (!($params['dto'] instanceof SmartDto)) {
			throw new SmartDtoException(sprintf('Cannot proxy value; [dto] in $params is not instance of [%s].', SmartDto::class));
		}
		$dto = $params['dto'];
		$value = $params['value'];
		$attribute = $value->getAttribute();
		[
			$service,
			$method,
		] = $attribute->getMetaOrThrow('proxy');
		if (!$dto->known($source = $attribute->getMetaOrThrow('source'))) {
			throw new SmartDtoException(sprintf('Requested unknown proxy property [%s::%s].', $dto->getName(), $source));
		} else if ($dto->isUndefined($source)) {
			return null;
		}
		return call_user_func([
			$this->container->get($service),
			$method,
		], $dto->getValue($source));
	}
}
