<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Reflection\Dto\ClassDto;
use Edde\Reflection\Dto\ConstantDto;
use Edde\Reflection\Dto\Property\AbstractProperty;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Sdk\NameResolverTrait;
use Edde\Sdk\SdkException;
use ReflectionException;
use function array_map;
use function implode;
use function json_encode;

class ClassGenerator {
	use NameResolverTrait;
	use GenericGeneratorTrait;
	use PropertyGeneratorTrait;

	/**
	 * @param ClassDto $classDto
	 *
	 * @return string
	 *
	 * @throws UnknownTypeException
	 * @throws SdkException
	 * @throws ReflectionException
	 */
	public function generate(ClassDto $classDto): ?string {
		return "export interface " . $this->genericGenerator->export($classDto) . " {\n" .
			implode("\n", array_map(function (AbstractProperty $property) {
				return $this->propertyGenerator->generate($property);
			}, $classDto->properties))
			. "\n}\n\n" .
			"export module " . $this->nameResolver->toExport($classDto->name) . " {\n" .
			implode("\n", array_map(function (ConstantDto $constantDto) {
				return "\texport const " . $constantDto->name . ' = ' . json_encode($constantDto->value) . ';';
			}, $classDto->constants))
			. "\n}\n";
	}
}
