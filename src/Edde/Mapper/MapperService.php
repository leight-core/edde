<?php
declare(strict_types=1);

namespace Edde\Mapper;

use Edde\Container\ContainerTrait;
use Edde\Mapper\Exception\MapperException;

class MapperService {
	use ContainerTrait;

	public function getMapper(?string $class): ?IMapper {
		if (!$class) {
			return null;
		} else if (!($mapper = $this->container->get($class)) instanceof IMapper) {
			throw new MapperException(sprintf('Class [%s] is not a mapper!', $class));
		}
		return $mapper;
	}
}
