<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Config\ConfigServiceTrait;
use Edde\Reflection\Dto\ClassDto;
use Edde\Reflection\Dto\Method\IRequestMethod;
use Edde\Reflection\Dto\Method\IResponseMethod;
use Edde\Reflection\Dto\Type\AbstractType;
use Edde\Reflection\Dto\Type\Utils\IClassType;
use Edde\Reflection\Dto\Type\Utils\IGenericType;
use Edde\Reflection\Exception\MissingReflectionClassException;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Reflection\ReflectionServiceTrait;
use Edde\Rest\IEndpoint;
use Edde\Rest\Reflection\Endpoint;
use Generator;
use ReflectionException;

class ClassExtractor {
	use ReflectionServiceTrait;
	use ConfigServiceTrait;

	/**
	 * @param Endpoint[] $endpoints
	 * @param string     $type
	 *
	 * @return Generator|Endpoint[]
	 */
	public function toExport(array $endpoints, string $type = IEndpoint::class): Generator {
		foreach ($endpoints as $endpoint) {
			if ($endpoint->is($type)) {
				yield $endpoint;
			}
		}
	}

	/**
	 * @param Endpoint[] $endpoints
	 *
	 * @return Generator|ClassDto[]
	 *
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 * @throws MissingReflectionClassException
	 */
	public function toClassList(array $endpoints): Generator {
		foreach ($this->toExport($endpoints) as $endpoint) {
			if ($endpoint->method instanceof IRequestMethod) {
				yield from $this->toYield($endpoint->method->request());
			}
			if ($endpoint->method instanceof IResponseMethod) {
				yield from $this->toYield($endpoint->method->response());
			}
		}
		foreach ($this->configService->system('sdk', [])['classes'] ?? [] as $fqdn) {
			yield from $this->toYield($this->reflectionService->toTypeString($fqdn));
		}
	}

	/**
	 * @param AbstractType $type
	 *
	 * @return Generator|ClassDto[]
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 */
	protected function toYield(AbstractType $type): Generator {
		if ($type instanceof IClassType) {
			yield ($classDto = $this->reflectionService->toClass($type->class()))->fqdn => $classDto;
			foreach ($classDto->properties as $property) {
				yield from $this->toYield($property);
			}
		} else if ($type instanceof IGenericType) {
			yield from $this->toYield($type->type());
			foreach ($type->generics() as $generic) {
				yield from $this->toYield($generic);
			}
		}
	}
}
