<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use DateTime;
use Edde\Reflection\Dto\ClassDto;
use Edde\Reflection\Dto\Method\IRequestMethod;
use Edde\Reflection\Dto\Method\IRequestResponseMethod;
use Edde\Reflection\Dto\Method\IResponseMethod;
use Edde\Reflection\Dto\Type\AbstractType;
use Edde\Reflection\Dto\Type\Utils\IClassType;
use Edde\Reflection\Dto\Type\Utils\IGenericType;
use Edde\Reflection\Dto\Type\Utils\IScalarType;
use Edde\Reflection\Dto\Type\Utils\ITemplateType;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Reflection\ReflectionServiceTrait;
use Edde\Rest\Api\IFetchEndpoint;
use Edde\Rest\Api\IListEndpoint;
use Edde\Rest\Api\IQueryEndpoint;
use Edde\Rest\Reflection\Endpoint;
use Edde\Sdk\ClassExtractorTrait;
use Edde\Sdk\NameResolverTrait;
use Edde\Sdk\SdkException;
use Generator;
use Minwork\Helper\Arr;
use ReflectionException;

class ImportGenerator {
	use ClassExtractorTrait;
	use NameResolverTrait;
	use ReflectionServiceTrait;
	use TypeGeneratorTrait;

	/**
	 * @param Endpoint[] $endpoints
	 *
	 * @return Generator
	 */
	public function generateQuery(array $endpoints): Generator {
		foreach ($this->classExtractor->toExport($endpoints, IQueryEndpoint::class) as $endpoint) {
			yield $endpoint->class->module => [
				"react",
				"FC",
			];
			yield $endpoint->class->module => [
				"@leight-core/leight",
				"useSourceContext",
			];
			yield $endpoint->class->module => [
				"@leight-core/leight",
				"ISourceContextProviderProps",
			];
			yield $endpoint->class->module => [
				"@leight-core/leight",
				"ISourceContext",
			];
			yield $endpoint->class->module => [
				"@leight-core/leight",
				"SourceContextProvider",
			];
			yield $endpoint->class->module => [
				"@leight-core/leight",
				"ITableProps",
			];
			yield $endpoint->class->module => [
				"@leight-core/leight",
				"Table",
			];
			yield $endpoint->class->module => [
				"@leight-core/leight",
				"IToOptionMapper",
			];
			yield $endpoint->class->module => [
				"@leight-core/leight",
				"IQuerySourceSelectProps",
			];
			yield $endpoint->class->module => [
				"@leight-core/leight",
				"QuerySourceSelect",
			];
			yield $endpoint->class->module => [
				"@leight-core/leight",
				"IQueryOptions",
			];
			yield $endpoint->class->module => [
				"@leight-core/leight",
				"IQueryResult",
			];
		}
	}

	/**
	 * @param Endpoint[] $endpoints
	 *
	 * @return Generator
	 *
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 */
	public function generateEndpoints(array $endpoints): Generator {
		static $map = [
			'post'   => [
				'createPostQuery',
				'createPostMutation',
			],
			'get'    => [
				'createGetQuery',
				'createGetMutation',
			],
			'put'    => [
				'createPutQuery',
				'createPutMutation',
			],
			'delete' => [
				'createDeleteQuery',
				'createDeleteMutation',
			],
			'patch'  => [
				'createPatchQuery',
				'createPatchMutation',
			],
		];

		foreach ($this->classExtractor->toExport($endpoints) as $endpoint) {
			foreach ($map[$endpoint->method->name] ?? [] as $package) {
				yield $endpoint->class->module => [
					"@leight-core/leight",
					$package,
				];
			}
			yield $endpoint->class->module => [
				"react-query",
				"useQueryClient",
			];
			if ($endpoint->method instanceof IRequestMethod) {
				yield from $this->yieldType($endpoint->class, $endpoint->method->request());
			}
			if ($endpoint->method instanceof IResponseMethod) {
				yield from $this->yieldType($endpoint->class, $endpoint->method->response());
			}
			if ($endpoint->method instanceof IRequestResponseMethod) {
				yield $endpoint->class->module => [
					"react",
					'FC',
				];
				yield $endpoint->class->module => [
					"@leight-core/leight",
					'IFormProps',
				];
				yield $endpoint->class->module => [
					"@leight-core/leight",
					'Form',
				];
			}
		}
	}

	/**
	 * @param ClassDto     $source
	 * @param AbstractType $target
	 *
	 * @return Generator
	 *
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 */
	protected function yieldType(ClassDto $source, AbstractType $target): Generator {
		if ($target instanceof IGenericType) {
			yield from $this->yieldType($source, $target->type());
			foreach ($target->generics() as $generic) {
				yield from $this->yieldType($source, $generic);
			}
		}
	}

	/**
	 * @param ClassDto $classDto
	 *
	 * @return Generator
	 *
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 */
	protected function yieldClassProperties(ClassDto $classDto): Generator {
		foreach ($classDto->properties as $property) {
			if ($property instanceof IClassType) {
				/**
				 * Skip classes not being exported.
				 */
				switch ($property->class()) {
					case DateTime::class:
						continue 2;
				}
				if ($classDto->module !== $property->module()) {
					yield $classDto->module => [
						"@/sdk/" . $property->module(),
						$this->reflectionService->toClass($property->class()),
					];
				}
				continue;
			} else if ($property instanceof IScalarType) {
				continue;
			} else if ($property instanceof ITemplateType) {
				continue;
			}
			throw new SdkException(sprintf("Unknown property type [%s] of [%s].", get_class($property), $classDto->fqdn));
		}
	}

	/**
	 * @param Endpoint[] $endpoints
	 *
	 * @return Generator
	 *
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 */
	public function generateClasses(array $endpoints): Generator {
		foreach ($this->classExtractor->toClassList($endpoints) as $classDto) {
			yield from $this->yieldClassProperties($classDto);
		}
	}

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
		$dependencies = [];

		foreach ($this->generateQuery($endpoints) as $module => [$package, $import]) {
			$dependencies[$module][$package][] = $import;
		}
		foreach ($this->generateEndpoints($endpoints) as $module => [$package, $import]) {
			$dependencies[$module][$package][] = $import;
		}

		foreach ($this->classExtractor->toExport($endpoints, IFetchEndpoint::class) as $endpoint) {
			$module = $endpoint->class->module;
			$dependencies[$module]["react"][] = "FC";
			$dependencies[$module]["@leight-core/leight"][] = "IQueryProps";
			$dependencies[$module]["@leight-core/leight"][] = "Query";
			$dependencies[$module]["@leight-core/leight"][] = "IEntityContext";
			$dependencies[$module]["@leight-core/leight"][] = "EntityContext";
			$dependencies[$module]["@leight-core/leight"][] = "EntityProvider";
			$dependencies[$module]["@leight-core/leight"][] = "IEntityProviderProps";
			$dependencies[$module]["@leight-core/leight"][] = "useOptionalContext";
			$dependencies[$module]["@leight-core/leight"][] = "useContext";
			$dependencies[$module]["@leight-core/leight"][] = "IPageProps";
			$dependencies[$module]["@leight-core/leight"][] = "useParams";
			$dependencies[$module]["@leight-core/leight"][] = "Page";
			$dependencies[$module]["@leight-core/leight"][] = "isCallable";
			$dependencies[$module]["react"][] = "createContext";
			$dependencies[$module]["react"][] = "ReactNode";
		}
		foreach ($this->classExtractor->toExport($endpoints, IListEndpoint::class) as $endpoint) {
			$module = $endpoint->class->module;
			$dependencies[$module]["react"][] = "FC";
			$dependencies[$module]["@leight-core/leight"][] = "IQueryProps";
			$dependencies[$module]["@leight-core/leight"][] = "Query";
		}

		foreach ($dependencies as $path => $imports) {
			yield $path => implode("\n", Arr::map($imports, function (string $package, array $imports) {
				$imports = array_map(function ($import) {
					if ($import instanceof ClassDto) {
						return $import->name;
					}
					return $import;
				}, array_unique($imports));
				sort($imports);
				$package = str_replace('/index', '', $package);
				if (count($imports) > 1) {
					return "import {\n\t" . implode(",\n\t", $imports) . "\n} from \"$package\";";
				}
				return "import {" . implode(", ", $imports) . "} from \"$package\";";
			}));
		}
	}
}
