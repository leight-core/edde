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
		/** @lang text */
		return <<<EXPORT
export const {$name}Context = createContext(null as unknown as IEntityContext<{$response}>);

export const use{$name}Context = (): IEntityContext<{$response}> => useContext({$name}Context, "{$name}Context");

export const useOptional{$name}Context = () => useOptionalContext<IEntityContext<{$response}>>({$name}Context as any);

export interface I{$name}Provider extends IEntityProviderProps<{$response}> {
}

export const {$name}Provider: FC<I{$name}Provider> = ({defaultEntity, children}) => {
	return <EntityProvider defaultEntity={defaultEntity}>
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

export type I{$name}PageExtra = ReactElement | ((entityContext: IEntityContext<{$response}>) => ReactElement);
export type I{$name}PageBreadcrumb = BreadcrumbProps | ReactElement<typeof Breadcrumb> | ((entityContext: IEntityContext<{$response}>) => BreadcrumbProps | ReactElement<typeof Breadcrumb>);

export interface I{$name}PageProps extends Omit<IPageProps, "breadcrumbProps" | "extra"> {
	children?: ReactNode | ((data: {$response}) => ReactNode);
	breadcrumbProps?: I{$name}PageBreadcrumb;
	breadcrumbMobileProps?: I{$name}PageBreadcrumb;
	breadcrumbBrowserProps?: I{$name}PageBreadcrumb;
	extra?: I{$name}PageExtra;
	extraMobile?: I{$name}PageExtra; 
	extraBrowser?: I{$name}PageExtra; 
}

export const {$name}Page: FC<I{$name}PageProps> = ({children, breadcrumbProps, breadcrumbMobileProps, breadcrumbBrowserProps, extraMobile, extraBrowser, extra, ...props}) => {
	const {{$param}} = useParams();
	extra = extra || entityContext => isBrowser ? extraBrowser : extraMobile;
	breadcrumbProps = breadcrumbProps || entityContext => isBrowser ? breadcrumbBrowserProps : breadcrumbMobileProps;
	return <{$name}Provider>
		<{$name}Context.Consumer>
			{entityContext => <Page
				breadcrumbProps={breadcrumbProps ? isCallable(breadcrumbProps) ? (breadcrumbProps as any)(entityContext) : breadcrumbProps : undefined}
				extra={extra ? (isCallable(extra) ? (extra as any)(entityContext) : extra) : undefined}
				{...props}
			>
				<Fetch{$name}
					query={{{$param}}}
				>
					{client => isCallable(children) ? (children as any)(client) : children}
				</Fetch{$name}>
			</Page>}
		</{$name}Context.Consumer>
	</{$name}Provider>;
};
EXPORT;
	}
}
