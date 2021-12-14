<?php
declare(strict_types=1);

namespace Edde\Php;

use Edde\Log\LoggerTrait;
use Edde\Php\Exception\MemoryException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MemoryUsageMiddleware implements MiddlewareInterface {
	use LoggerTrait;
	use MemoryServiceTrait;

	/**
	 * @param ServerRequestInterface  $request
	 * @param RequestHandlerInterface $handler
	 *
	 * @return ResponseInterface
	 *
	 * @throws MemoryException
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		$before = $this->memoryService->getUsage();
		$result = $handler->handle($request);
		$after = $this->memoryService->getUsage();
		if ($this->memoryService->isHighPeak(90)) {
			$this->logger->error(vsprintf('[%s] High memory usage (> 90%% of limit): before [%s], after [%s] (~%s); peak [%s]; limit [%s].', [
				(string)$request->getUri(),
				$before->format(),
				$after->format(),
				$after->remove($before)->format(),
				$this->memoryService->getPeak()->format(),
				$this->memoryService->getLimit()->format(),
			]), [
				'uri'  => (string)$request->getUri(),
				'tags' => ['memory'],
			]);
		} else if ($this->memoryService->isHighPeak(75)) {
			$this->logger->warning(vsprintf('[%s] High memory usage (> 80%% of limit): before [%s], after [%s] (~%s); peak [%s]; limit [%s].', [
				(string)$request->getUri(),
				$before->format(),
				$after->format(),
				$after->remove($before)->format(),
				$this->memoryService->getPeak()->format(),
				$this->memoryService->getLimit()->format(),
			]), [
				'uri'  => (string)$request->getUri(),
				'tags' => ['memory'],
			]);
		}
		return $result;
	}

	/**
	 * @param ServerRequestInterface  $request
	 * @param RequestHandlerInterface $handler
	 *
	 * @return ResponseInterface
	 *
	 * @throws MemoryException
	 */
	public function __invoke(
		ServerRequestInterface  $request,
		RequestHandlerInterface $handler
	) {
		return $this->process($request, $handler);
	}
}
