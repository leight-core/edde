<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use Edde\Container\ContainerTrait;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Dto\Value;

class ProxyDtoMapper extends AbstractDtoMapper {
    use ContainerTrait;

    protected function handle($item, Value $value, SmartDto $dto) {
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
