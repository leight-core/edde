<?php
declare(strict_types=1);

namespace Edde\Reader;

use Edde\Dto\DtoServiceTrait;
use Edde\Reflection\Exception\MissingReflectionClassException;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Reflection\ReflectionServiceTrait;
use Generator;
use ReflectionException;

abstract class AbstractReader implements IReader {
	use DtoServiceTrait;
	use ReflectionServiceTrait;

	/**
	 * @param Generator $generator
	 *
	 * @return Generator
	 *
	 * @throws MissingReflectionClassException
	 * @throws UnknownTypeException
	 * @throws ReflectionException
	 */
	public function stream(Generator $generator): Generator {
		$dto = $this->reflectionService->toClass(static::class)->getRequestClassOf('handle');
		foreach ($generator as $item) {
			yield $this->handle($dto ? $this->dtoService->fromArray($dto, $item) : $item);
		}
	}

	public function read(Generator $generator): void {
		foreach ($this->stream($generator) as $_) ;
	}
}
