<?php
declare(strict_types=1);

namespace Edde\Rest\Endpoint;

use Edde\Cache\CacheTrait;
use Edde\Dto\DtoServiceTrait;
use Edde\Http\HttpIndexTrait;
use Edde\Log\LoggerTrait;
use Edde\Profiler\ProfilerServiceTrait;
use Edde\Query\Dto\Query;
use Edde\Reflection\Dto\Method\IRequestMethod;
use Edde\Repository\Exception\DuplicateEntryException;
use Edde\Rest\Exception\ClientException;
use Edde\Rest\Exception\RestException;
use Edde\Rest\IEndpoint;
use Edde\Rest\Reflection\Endpoint;
use Edde\Slim\Response;
use Edde\User\CurrentUserServiceTrait;
use Nette\Utils\JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouteInterface;
use Slim\Routing\RouteContext;
use Throwable;
use function in_array;
use function is_string;

abstract class AbstractEndpoint implements IEndpoint {
	use LoggerTrait;
	use DtoServiceTrait;
	use HttpIndexTrait;
	use ProfilerServiceTrait;
	use CacheTrait;
	use CurrentUserServiceTrait;

	/** @var ServerRequestInterface */
	protected $request;
	/** @var ResponseInterface */
	protected $response;
	/** @var mixed */
	protected $body;
	/** @var RouteInterface */
	protected $route;
	/** @var Endpoint */
	protected $endpoint;

	/**
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface      $response
	 *
	 * @return ResponseInterface
	 *
	 * @throws JsonException
	 */
	public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
		return $this->profilerService->profile(static::class, function () use ($request, $response) {
			$this->request = $request;
			$this->response = $response;
			$this->body = $this->request->getParsedBody();
			$this->route = RouteContext::fromRequest($request)->getRoute();
			$this->endpoint = $this->httpIndex->endpoint(static::class);
			try {
				$args = [];
				/** @var $method IRequestMethod */
				if (($method = $this->endpoint->method) instanceof IRequestMethod) {
					$args = [
						$this->dtoService->fromObject($method->toClass(), $this->request->getParsedBody()),
					];
				}
				$result = $this->{$this->endpoint->method->name}(...$args);
				return $result instanceof ResponseInterface ? $result : Response::withJson($response, $result);
			} catch (Throwable $e) {
				$this->logger->error('Endpoint exception, cache cleared!', ['tags' => ['request']]);
				/**
				 * This could be quite costly, but also a bit safe way how to reset application if there is an error
				 * due to cache problems; in general exception in this place is real exception (aka ugly error, bug), thus
				 * slow down caused by this could be a pointer that something is wrong.
				 */
				$this->cache->clear();
				return $this->handleException($e, $response);
			}
		});
	}

	/**
	 * @param Throwable         $throwable
	 * @param ResponseInterface $response
	 *
	 * @return ResponseInterface
	 *
	 * @throws JsonException
	 */
	protected function handleException(Throwable $throwable, ResponseInterface $response): ResponseInterface {
		$this->logger->error($throwable);
		try {
			throw $throwable;
		} catch (RestException|ClientException $e) {
			return Response::withJson($response, $e->getMessage(), $e->getCode() > 0 ? $e->getCode() : 400);
		} catch (DuplicateEntryException $e) {
			return Response::withJson($response, $e->getMessage(), 409);
		} catch (Throwable $e) {
			$this->logger->error($e);
			return Response::withJson($response, 'Uncatched server error. This is just a bug. Sorry.', 500);
		}
	}

	/**
	 * @param string $name
	 *
	 * @return string|null
	 *
	 * @throws RestException
	 */
	protected function param(string $name): ?string {
		if (!in_array($name, $this->endpoint->query) || !is_string($param = $this->route->getArgument($name))) {
			throw new RestException("Missing URL parameter [$name].", 400);
		}
		return $param;
	}

	/**
	 * Get an item from the $_POST.
	 *
	 * @param string     $name
	 * @param mixed|null $default
	 *
	 * @return mixed|null
	 */
	protected function ofBody(string $name, $default = null) {
		return $this->body[$name] ?? $default;
	}

	protected function withUser(Query $query): Query {
		return $query->withFilter([
			'userId' => $this->currentUserService->requiredId(),
		]);
	}
}
