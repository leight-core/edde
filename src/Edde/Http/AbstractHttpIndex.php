<?php
declare(strict_types=1);

namespace Edde\Http;

use Edde\Cache\DatabaseCacheTrait;
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
use Psr\SimpleCache\InvalidArgumentException;
use ReflectionException;
use function array_diff;
use function array_diff_key;
use function array_flip;
use function array_keys;
use function array_map;
use function array_merge;
use function array_unique;
use function array_values;
use function count;
use function explode;
use function get_class;
use function implode;
use function reset;
use function sprintf;
use function str_replace;

abstract class AbstractHttpIndex implements IHttpIndex {
	use DatabaseCacheTrait;
	use ReflectionServiceTrait;
	use ProfilerServiceTrait;

	/** @var string[] */
	protected $index;

	/**
	 * @param string $endpoint
	 */
	public function register(string $endpoint): void {
		$this->index[$endpoint] = $endpoint;
	}

	/**
	 * @return Endpoint[]
	 *
	 * @throws HttpException
	 * @throws InvalidArgumentException
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 */
	public function endpoints(callable $onRebuild = null): array {
		return $this->profilerService->profile(static::class, function () use ($onRebuild) {
			return $this->databaseCache->get('endpoints', function () use ($onRebuild) {
				$onRebuild && $onRebuild();
				$this->databaseCache->set('endpoints', $endpoints = array_map(function (string $name) {
					return $this->endpoint($name);
				}, $this->index));
				return $endpoints;
			});
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
		return $this->databaseCache->get('endpoint.' . $name, function () use ($name) {
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
				'link'   => $class->annotations['link'] ?? '/' . str_replace([
						'/endpoint/',
						'-endpoint',
						'marsh/',
					], [
						'/',
						'',
					], implode('/', array_map(function (string $part) {
						return StringUtils::recamel($part);
					}, explode('\\', $class->fqdn)))) . implode('', array_map(function (string $param) {
						return '/{' . $param . '}';
					}, array_unique($query))),
			];

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

			$this->databaseCache->set('endpoints.' . $name, $endpoint);
			return $endpoint;
		});
	}
}
