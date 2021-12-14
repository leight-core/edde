<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Reflection\Dto\Property\AbstractProperty;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Sdk\SdkException;
use ReflectionException;

class PropertyGenerator {
	use GenericGeneratorTrait;
	use TypeGeneratorTrait;

	/**
	 * @param AbstractProperty $property
	 *
	 * @return string|null
	 *
	 * @throws UnknownTypeException
	 * @throws SdkException
	 * @throws ReflectionException
	 */
	public function generate(AbstractProperty $property): ?string {
		if ($property->isInternal) {
			return null;
		}
		$type = $this->typeGenerator->resolve($property);
		$separator = $property->isOptional ? "?:" : ":";
		return "\t" . $property->name . "$separator $type;";
	}
}
