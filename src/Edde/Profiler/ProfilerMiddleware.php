<?php
declare(strict_types=1);

namespace Edde\Profiler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ProfilerMiddleware implements MiddlewareInterface {
	use ProfilerServiceTrait;

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		return $this->profilerService->profile((string)$request->getUri(), function () use ($request, $handler) {
			return $handler->handle($request);
		});
	}

	/**
	 * @param ServerRequestInterface  $request
	 * @param RequestHandlerInterface $handler
	 *
	 * @return ResponseInterface
	 */
	public function __invoke(
		ServerRequestInterface  $request,
		RequestHandlerInterface $handler
	) {
		return $this->process($request, $handler);
	}
}
