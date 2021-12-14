<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Reflection\Dto\ClassDto;
use Edde\Reflection\Dto\TemplateDto;
use Edde\Reflection\Exception\MissingReflectionClassException;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Reflection\ReflectionServiceTrait;
use Edde\Sdk\NameResolverTrait;
use Edde\Sdk\SdkException;
use ReflectionClass;
use ReflectionException;

class GenericGenerator {
	use NameResolverTrait;
	use TypeGeneratorTrait;
	use ReflectionServiceTrait;

	/**
	 * @param ClassDto $classDto
	 *
	 * @return string
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 * @throws MissingReflectionClassException
	 */
	public function toClass(ClassDto $classDto): string {
		if (!$classDto->hasTemplates) {
			return '';
		}
		$class = new ReflectionClass($classDto->fqdn);
		return "<" . implode(", ", array_map(function (TemplateDto $templateDto) use ($class) {
				return $templateDto->name . ($templateDto->default ? ' = ' . $this->typeGenerator->resolve($this->reflectionService->toTypeString($templateDto->default, $class)) : '');
			}, $classDto->templates)) . ">";
	}

	/**
	 * @param ClassDto $classDto
	 *
	 * @return string
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 */
	public function export(ClassDto $classDto): string {
		return $this->nameResolver->toExport($classDto->name) . $this->toClass($classDto);
	}
}
