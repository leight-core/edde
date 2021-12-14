<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Rest\Api\IFetchEndpoint;
use Edde\Rest\Api\IListEndpoint;
use Edde\Rest\Api\IMutationEndpoint;
use Edde\Rest\Api\IQueryEndpoint;
use Edde\Rest\Reflection\Endpoint;
use Edde\Sdk\ClassExtractorTrait;
use Edde\Sdk\NameResolverTrait;
use Edde\Sdk\SdkException;
use Generator;
use ReflectionException;
use Throwable;

class ModuleGenerator {
	use ClassExtractorTrait;
	use ImportGeneratorTrait;
	use ClassGeneratorTrait;
	use EndpointGeneratorTrait;
	use QueryGeneratorTrait;
	use FetchGeneratorTrait;
	use NameResolverTrait;
	use ListGeneratorTrait;
	use FormGeneratorTrait;

	/**
	 * @param Endpoint[] $endpoints
	 *
	 * @return Generator|string[]
	 *
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 */
	public function generate(array $endpoints): Generator {
		$export = [];

		foreach ($this->importGenerator->generate($endpoints) as $path => $source) {
			$export[$path] = $export[$path] ?? [];
			$export[$path][] = $source;
		}

		foreach ($this->classExtractor->toClassList($endpoints) as $classDto) {
			$export[$classDto->module][$classDto->fqdn . '/class'] = $this->classGenerator->generate($classDto);
		}
		foreach ($this->classExtractor->toExport($endpoints) as $endpoint) {
			try {
				$export[$endpoint->class->module][$endpoint->class->fqdn . '/endpoint'] = $this->endpointGenerator->generate($endpoint);
			} catch (Throwable $throwable) {
				throw new SdkException(sprintf("Endpoint [%s] generation failed: %s", $endpoint->class->fqdn, $throwable->getMessage()), 0, $throwable);
			}
		}
		foreach ($this->classExtractor->toExport($endpoints, IMutationEndpoint::class) as $endpoint) {
			$export[$endpoint->class->module][$endpoint->class->fqdn . '/form'] = $this->formGenerator->generate($endpoint);
		}
		foreach ($this->classExtractor->toExport($endpoints, IFetchEndpoint::class) as $endpoint) {
			$export[$endpoint->class->module][$endpoint->class->fqdn . '/fetch'] = $this->fetchGenerator->generate($endpoint);
		}
		foreach ($this->classExtractor->toExport($endpoints, IListEndpoint::class) as $endpoint) {
			$export[$endpoint->class->module][$endpoint->class->fqdn . '/list'] = $this->listGenerator->generate($endpoint);
		}
		foreach ($this->classExtractor->toExport($endpoints, IQueryEndpoint::class) as $endpoint) {
			$export[$endpoint->class->module][] = $this->queryGenerator->generate($endpoint);
		}

		foreach ($export as $path => $sources) {
			yield "$path.tsx" => implode("\n\n", array_filter($sources));
		}
	}
}
