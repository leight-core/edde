<?php
declare(strict_types=1);

namespace Edde\Session;

use Edde\Log\LoggerTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Throwable;

final class SessionMiddleware implements MiddlewareInterface {
	use LoggerTrait;

	/** @var Session */
	private $session;

	public function __construct(Session $session) {
		$this->session = $session;
	}

	public function process(
		ServerRequestInterface  $request,
		RequestHandlerInterface $handler
	): ResponseInterface {
		try {
			/**
			 * Because the app runs in the *** environment where session stuff is heavily unreliable,
			 * this is just to keep the app (hopefully) alive.
			 */
			if (!$this->session->isStarted()) {
				$this->session->start();
			}
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
		}
		return $handler->handle($request);
	}

	public function __invoke(
		ServerRequestInterface  $request,
		RequestHandlerInterface $handler
	) {
		return $this->process($request, $handler);
	}
}
