<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Reflection\ReflectionServiceTrait;
use Edde\Rest\EndpointInfoTrait;
use Edde\Rest\Reflection\QueryEndpoint;
use Edde\Sdk\NameResolverTrait;
use Edde\Sdk\SdkException;
use ReflectionException;

class QueryGenerator {
	use NameResolverTrait;
	use TypeGeneratorTrait;
	use ReflectionServiceTrait;
	use EndpointInfoTrait;

	/**
	 * @param QueryEndpoint $endpoint
	 *
	 * @return string|null
	 *
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 */
	public function generate(QueryEndpoint $endpoint): ?string {
		$name = $this->nameResolver->toExport($endpoint->class->name);
		$query = "I" . $name . "QueryParams";
		$item = $this->typeGenerator->resolve($endpoint->item);
		$orderBy = $this->typeGenerator->resolve($endpoint->orderBy);
		$filter = $this->typeGenerator->resolve($endpoint->filter);

		$generic = "I{$name}QueryParams, $item, $orderBy, $filter";

		return <<<EXPORT
export const use{$name}Source = () => useSourceContext<$generic>()

export interface I{$name}SourceContext extends ISourceContext<$generic> {
}

export interface I{$name}SourceProps extends Partial<ISourceContextProviderProps<$generic>> {
}

export const {$name}Source: FC<I{$name}SourceProps> = ({children, ...props}) => {
	return <SourceContextProvider<$generic>
		useQuery={use{$name}Query}
		{...props}
	>
		{children}
	</SourceContextProvider>;
}

export interface I{$name}BaseTableProps extends ITableProps<$generic> {
}

export const {$name}BaseTable: FC<I{$name}BaseTableProps> = props => {
	return <Table<$generic>
		{...props}
	/>
}

export interface I{$name}SourceTableProps extends I{$name}BaseTableProps {
	source?: I{$name}SourceProps;
	defaultFilter?: $filter;
	defaultOrderBy?: $orderBy;
	defaultQuery?: $query;
	filter?: $filter;
	orderBy?: $orderBy;
	query?: $query;
	options?: IQueryOptions<IQueryResult<$item>>;
}

export const {$name}SourceTable: FC<I{$name}SourceTableProps> = ({source, defaultFilter, defaultOrderBy, defaultQuery, filter, orderBy, query, options, ...props}) => {
	return <{$name}Source
		defaultFilter={defaultFilter}
		defaultOrderBy={defaultOrderBy}
		defaultQuery={defaultQuery}
		filter={filter}
		orderBy={orderBy}
		query={query}
		options={options}
		{...source}
	>
		<{$name}BaseTable {...props}/>
	</{$name}Source>
}

export interface I{$name}SourceSelectProps extends Partial<IQuerySourceSelectProps<$item>> {
	toOption: IToOptionMapper<$item>;
	source?: I{$name}SourceProps;
}

export const {$name}SourceSelect: FC<I{$name}SourceSelectProps> = ({source, ...props}) => {
	return <{$name}Source defaultSize={100} {...source}>
		<QuerySourceSelect<$generic> {...props}/>
	</{$name}Source>;
};

export interface I{$name}FilterContextProps extends Partial<IFilterContextProviderProps<$filter>> {
}

export const {$name}FilterContext: FC<I{$name}FilterContextProps> = props => {
	return <FilterContextProvider<$filter> {...props}/>
}
EXPORT;
	}
}
