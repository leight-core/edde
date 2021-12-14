<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Rest\EndpointInfoTrait;
use Edde\Rest\Reflection\FetchEndpoint;
use Edde\Sdk\NameResolverTrait;
use Edde\Sdk\SdkException;
use ReflectionException;
use function count;
use function reset;

class FetchGenerator {
	use NameResolverTrait;
	use TypeGeneratorTrait;
	use EndpointInfoTrait;

	/**
	 * @param FetchEndpoint $endpoint
	 *
	 * @return string
	 *
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 */
	public function generate(FetchEndpoint $endpoint): ?string {
		if (!count($endpoint->query)) {
			return null;
		}
		$name = $this->nameResolver->toExport($endpoint->class->name);
		$method = $this->nameResolver->toExport($endpoint->class->name);
		$response = $this->typeGenerator->resolve($endpoint->response);
		$required = count($endpoint->query) > 0 ? ":" : "?:";
		$param = reset($endpoint->query);
		return <<<EXPORT
export const {$name}Context = createContext(null as unknown as IEntityContext<{$response}>);

export const use{$name}Context = (): IEntityContext<{$response}> => useContext({$name}Context, "{$name}Context");

export const useOptional{$name}Context = () => useOptionalContext<IEntityContext<{$response}>>({$name}Context as any);

export interface I{$name}Provider extends IEntityProviderProps<{$response}> {
}

export const {$name}Provider: FC<I{$name}Provider> = ({defaultEntity, children}) => {
	return <EntityProvider defaultEntity="{defaultEntity}">
		<EntityContext.Consumer>
			{entityContext => <{$name}Context.Provider value={entityContext}>
				{children}
			</{$name}Context.Provider>}
		</EntityContext.Consumer>
	</EntityProvider>;
};

export interface IFetch{$name}Props extends Partial<IQueryProps<I{$name}QueryParams, void, {$response}>> {
	query$required I{$name}QueryParams;
}

export const Fetch{$name}: FC<IFetch{$name}Props> = ({query, ...props}) => <Query<I{$name}QueryParams, void, {$response}>
	useQuery={use{$method}Query}
	query={query}
	request={undefined}
	context={useOptional{$name}Context()}
	{...props}
/>;

export interface I{$name}PageProps extends IPageProps {
	children?: ReactNode | ((data: {$response}) => ReactNode);
}

export const {$name}Page: FC<I{$name}PageProps> = ({children, ...props}) => {
	const {{$param}} = useParams();
	return <{$name}Provider>
		<Page {...props}>
			<Fetch{$name}
				query={{{$param}}}
			>
				{client => isCallable(children) ? (children as any)(client) : children}
			</Fetch{$name}>
		</Page>
	</{$name}Provider>;
};
EXPORT;
	}
}
