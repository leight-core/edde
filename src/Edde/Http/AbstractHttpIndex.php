<?php
declare(strict_types=1);

namespace Edde\Http;

use Edde\Http\Exception\HttpException;
use Edde\Profiler\ProfilerServiceTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Reflection\Dto\Method\IRequestResponseMethod;
use Edde\Reflection\Dto\Method\IResponseMethod;
use Edde\Reflection\Dto\TemplateDto;
use Edde\Reflection\Dto\Type\Utils\IClassType;
use Edde\Reflection\Dto\Type\Utils\IGenericType;
use Edde\Reflection\Exception\MissingReflectionClassException;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Reflection\ReflectionServiceTrait;
use Edde\Rest\IFetchEndpoint;
use Edde\Rest\IListEndpoint;
use Edde\Rest\IMutationEndpoint;
use Edde\Rest\IQueryEndpoint;
use Edde\Rest\Reflection\Endpoint;
use Edde\Rest\Reflection\FetchEndpoint;
use Edde\Rest\Reflection\ListEndpoint;
use Edde\Rest\Reflection\MutationEndpoint;
use Edde\Rest\Reflection\QueryEndpoint;
use Edde\Utils\StringUtils;
use Nette\Utils\Strings;
use Psr\SimpleCache\InvalidArgumentException;
use ReflectionException;
use function array_diff;
use function array_diff_key;
use function array_flip;
use function array_keys;
use function array_map;
use function array_merge;
use function array_pop;
use function array_unique;
use function array_values;
use function count;
use function explode;
use function get_class;
use function implode;
use function lcfirst;
use function reset;
use function sprintf;
use function str_replace;
use function strlen;
use function trim;

abstract class AbstractHttpIndex implements IHttpIndex {
	use ReflectionServiceTrait;
	use ProfilerServiceTrait;
	use LinkFilterTrait;

	/** @var string[] */
	protected $index;

	/**
	 * @param string $endpoint
	 */
	public function register(string $endpoint): void {
		$this->index[$endpoint] = $endpoint;
	}

	/**
	 * @param callable|null $onRebuild
	 *
	 * @return Endpoint[]
	 *
	 * @throws HttpException
	 * @throws InvalidArgumentException
	 * @throws MissingReflectionClassException
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 */
	public function endpoints(callable $onRebuild = null): array {
		return $this->profilerService->profile(static::class, function () use ($onRebuild) {
			$onRebuild && $onRebuild();
			return array_map(function (string $name) {
				return $this->endpoint($name);
			}, $this->index);
		});
	}

	/**
	 * @param string $name
	 *
	 * @return Endpoint
	 *
	 * @throws HttpException
	 * @throws InvalidArgumentException
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 * @throws MissingReflectionClassException
	 */
	public function endpoint(string $name): Endpoint {
		static $methods = [
			'get',
			'post',
			'put',
			'patch',
			'delete',
		];
		$class = $this->reflectionService->toClass($name);
		$diff = array_diff($methods, array_keys(array_diff_key(array_flip($methods), $class->methods)));
		$method = $class->methods[reset($diff)] ?? null;
		if (!$method) {
			throw new HttpException(sprintf('Endpoint [%s] has no supported method (%s).', $name, implode(', ', $methods)));
		}
		if (count($diff) !== 1) {
			throw new HttpException(sprintf('Endpoint [%s] has more methods (%s); specify exactly one of (%s).', $name, implode(', ', $diff), implode(', ', $methods)));
		}

		$defaults = [
			'class'  => $class,
			'method' => $method,
			'query'  => $query = ((array)($class->annotations['query'] ?? [])),
			'roles'  => (array)($class->annotations['roles'] ?? []),
			'link'   => $class->annotations['link'] ?? ('/' . $this->linkFilter->filter(str_replace([
							'/endpoint/',
							'-endpoint',
						], [
							'/',
							'',
						], implode('/', array_map(function (string $part) {
							if ($part[0] === '_' && $part[strlen($part) - 1] === '_') {
								return "{" . lcfirst(trim($part, '_')) . "}";
							}
							return StringUtils::recamel($part);
						}, explode('\\', $class->fqdn)))) . implode('', array_map(function (string $param) {
							return '/{' . $param . '}';
						}, array_unique($query))))),
		];

		if (!empty($class->annotations['alterLink'])) {
			$link = explode('/', trim($defaults['link'], '/'));
			array_pop($link);
			$defaults['link'] = '/' . implode('/', $link) . $class->annotations['alterLink'];
		}

		foreach (Strings::matchAll($defaults['link'], '~{([a-zA-Z]+)}~') as [, $param]) {
			$defaults['query'][] = $param;
		}
		$defaults['query'] = array_unique($defaults['query'] ?? []);

		$endpoint = Endpoint::create($defaults);
		if ($class->is(IQueryEndpoint::class)) {
			if (!$method instanceof IRequestResponseMethod) {
				throw new HttpException(sprintf('Query endpoint [%s] does not have request/response method [%s] (check method types).', $name, $method->name));
			}
			/** @var $request IGenericType|IClassType */
			if (!($request = $method->request()) instanceof IGenericType && !$request instanceof IClassType) {
				throw new HttpException(sprintf('Query endpoint [%s] does not have generic/class request type for method [%s]!', $name, $method->name));
			}
			/** @var $response IGenericType */
			if (!($response = $method->response()) instanceof IGenericType) {
				throw new HttpException(sprintf('Query endpoint [%s] does not have generic response type for method [%s]!', $name, $method->name));
			}
			/** @var $class IClassType */
			if (!(($class = $response->type()) instanceof IClassType) || $class->class() !== QueryResult::class) {
				throw new HttpException(sprintf('Response of method [%s] of query endpoint [%s] is not required [%s] response.', $method->name, $name, QueryResult::class));
			}

			$pageRequestClass = $this->reflectionService->toClass(Query::class);
			$pageRequestReflection = $pageRequestClass->reflection();

			$generics = array_values(array_map(function (TemplateDto $templateDto) use ($pageRequestReflection) {
				return $this->reflectionService->toTypeString($templateDto->default, $pageRequestReflection);
			}, $pageRequestClass->templates));

			$orderBy = null;
			$filter = null;
			if ($request instanceof IClassType) {
				[
					$orderBy,
					$filter,
				] = $generics;
			}
			if ($request instanceof IGenericType) {
				[
					$orderBy,
					$filter,
				] = (array_values($request->generics()) + $generics);
			}

			$endpoint = QueryEndpoint::create(array_merge($defaults, [
				/**
				 * Because we know $response is IGenericType, there must be at least one generic parameter.
				 */
				'item'    => $response->generics()[0],
				'filter'  => $filter,
				'orderBy' => $orderBy,
			]));
		} else if ($class->is(IFetchEndpoint::class)) {
			if (!($endpoint->method instanceof IResponseMethod)) {
				throw new HttpException(sprintf('Fetch endpoint [%s] does not have response method [%s]!', $name, get_class($endpoint->method)));
			}
			$endpoint = FetchEndpoint::create(array_merge($defaults, [
				'response' => $method->response(),
			]));
		} else if ($class->is(IListEndpoint::class)) {
			if (!($endpoint->method instanceof IResponseMethod)) {
				throw new HttpException(sprintf('List endpoint [%s] does not have response method [%s]!', $name, get_class($endpoint->method)));
			}
			if (!($endpoint->method->response()->isArray)) {
				throw new HttpException(sprintf('List endpoint [%s] does not return an array of items!', $name));
			}
			$endpoint = ListEndpoint::create(array_merge($defaults, [
				'item' => $method->response(),
			]));
		} else if ($class->is(IMutationEndpoint::class)) {
			$endpoint = MutationEndpoint::create(array_merge($defaults, []));
		}
		return $endpoint;
	}
}
