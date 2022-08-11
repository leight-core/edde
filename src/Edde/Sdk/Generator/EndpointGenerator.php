<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Reflection\Dto\Method\IRequestMethod;
use Edde\Reflection\Dto\Method\IResponseMethod;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Rest\EndpointInfoTrait;
use Edde\Rest\Reflection\Endpoint;
use Edde\Rest\Reflection\MutationEndpoint;
use Edde\Sdk\NameResolverTrait;
use Edde\Sdk\SdkException;
use ReflectionException;

class EndpointGenerator {
	use GenericGeneratorTrait;
	use EndpointInfoTrait;
	use NameResolverTrait;
	use TypeGeneratorTrait;

	/**
	 * @param Endpoint $endpoint
	 *
	 * @return string|null
	 *
	 * @throws UnknownTypeException
	 * @throws SdkException
	 * @throws ReflectionException
	 */
	public function generate(Endpoint $endpoint): ?string {
		$name = $this->nameResolver->toExport($endpoint->class->name);
		$request = 'void';
		$response = 'void';

		static $templates = [
			'get'    => [
				'query'    => "export const use{name}Query = createGetQuery<I{name}QueryParams, {response}>(\"{id}\")",
				'mutation' => "export const use{name}Mutation = createGetMutation<I{name}QueryParams, {response}>(\"{id}\")",
			],
			'delete' => [
				'query'    => "export const use{name}Query = createDeleteQuery<I{name}QueryParams, {response}>(\"{id}\")",
				'mutation' => "export const use{name}Mutation = createDeleteMutation<I{name}QueryParams, {response}>(\"{id}\")",
			],
			'post'   => [
				'query'    => "export const use{name}Query = createPostQuery<I{name}QueryParams, {pair}>(\"{id}\")",
				'mutation' => "export const use{name}Mutation = createPostMutation<I{name}QueryParams, {pair}>(\"{id}\")",
			],
			'patch'  => [
				'query'    => "export const use{name}Query = createPatchQuery<I{name}QueryParams, {pair}>(\"{id}\")",
				'mutation' => "export const use{name}Mutation = createPatchMutation<I{name}QueryParams, {pair}>(\"{id}\")",
			],
			'put'    => [
				'query'    => "export const use{name}Query = createPutQuery<I{name}QueryParams, {pair}>(\"{id}\")",
				'mutation' => "export const use{name}Mutation = createPutMutation<I{name}QueryParams, {pair}>(\"{id}\")",
			],
		];

		if ($endpoint->method instanceof IRequestMethod) {
			$request = $this->typeGenerator->resolve($endpoint->method->request());
		}
		if ($endpoint->method instanceof IResponseMethod) {
			$response = $this->typeGenerator->resolve($endpoint->method->response());
		}
		$id = $endpoint->link;
		$pair = "$request, $response";

		$params = "{\n\t" . implode("\n\t", array_map(function (string $name) {
				return "$name: string;";
			}, $endpoint->query)) . "\n}\n\n";

		$export[] = "export type I{$name}QueryParams = " . (count($endpoint->query) ? $params : "void;\n\n");
		$export[] = str_replace([
				'{name}',
				'{request}',
				'{response}',
				'{pair}',
				'{id}',
			], [
				$name,
				$request,
				$response,
				$pair,
				$id,
			], $templates[$endpoint->method->name][$endpoint instanceof MutationEndpoint ? 'mutation' : 'query']) . ";";

		if (!$endpoint instanceof MutationEndpoint) {
			$export[] = <<<EXPORT
export const use{$name}QueryInvalidate = () => {
	const queryClient = useQueryClient();
	return () => queryClient.invalidateQueries(["{$id}"])
}
EXPORT;
		}

		return implode("\n", $export);
	}
}
