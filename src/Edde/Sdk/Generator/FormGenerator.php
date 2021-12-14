<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Reflection\Dto\Method\IRequestResponseMethod;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Rest\Reflection\MutationEndpoint;
use Edde\Sdk\NameResolverTrait;
use Edde\Sdk\SdkException;
use ReflectionException;

class FormGenerator {
	use NameResolverTrait;
	use TypeGeneratorTrait;

	/**
	 * @param MutationEndpoint $endpoint
	 *
	 * @return string|null
	 *
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 */
	public function generate(MutationEndpoint $endpoint): ?string {
		if (!($endpoint->method instanceof IRequestResponseMethod)) {
			return null;
		}
		$name = $this->nameResolver->toExport($endpoint->class->name);
		$request = $this->typeGenerator->resolve($endpoint->method->request());
		$response = $this->typeGenerator->resolve($endpoint->method->response());
		$generics = "I{$name}QueryParams, $request, $response";

		return <<<EXPORT
export interface I{$name}FormProps extends Partial<IFormProps<{$generics}>> {
}

export const {$name}Form: FC<I{$name}FormProps> = props => {
	return <Form<{$generics}>
		useMutation={use{$name}Mutation}
		{...props}
	/>
}
EXPORT;
	}
}
