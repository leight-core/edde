<?php
declare(strict_types=1);

namespace Edde\Rpc\Service;

use DI\DependencyException;
use DI\NotFoundException;
use Edde\Container\ContainerTrait;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Log\LoggerTrait;
use Edde\Rpc\Exception\RpcException;
use Throwable;

class RpcService {
	use ContainerTrait;
	use LoggerTrait;
	use SmartServiceTrait;

	/**
	 * @param string $name
	 *
	 * @return IRpcHandler
	 * @throws NotFoundException
	 * @throws RpcException
	 * @throws DependencyException
	 */
	public function resolve(string $name): IRpcHandler {
		$service = $this->container->get($name);
		if (!$service instanceof IRpcHandler) {
			throw new RpcException(sprintf('Requested service [%s] is not a handler.', $name));
		}
		return $service;
	}

	public function execute(SmartDto $dto) {
		$response = [];
		/** @var $bulk SmartDto */
		foreach ($dto->get('bulk')->get() as $id => $bulk) {
			$name = $bulk->get('service')->get();
			try {
				$service = $this->resolve($name);
				$result = $service->handle(
					($requestSchema = $service->getRequestMeta()->getSchema()) ? $this->smartService->from((object)$bulk->getValue('data'), $requestSchema) : SmartDto::ofDummy()
				);
				$response[$id] = (object)[
					'data' => $result ? ($result instanceof SmartDto ? $result->export() : $result) : null,
				];
			} catch (NotFoundException $exception) {
				$this->logger->error($exception);
				$response[$id] = (object)[
					'error' => (object)[
						'message' => sprintf('Unknown RPC service [%s].', $name),
						'code' => $exception->getCode(),
					],
				];
			} catch (RpcException $exception) {
				$this->logger->error($exception);
				$response[$id] = (object)[
					'error' => (object)[
						'message' => $exception->getMessage(),
						'code' => $exception->getCode(),
					],
				];
			} catch (Throwable $exception) {
				$this->logger->error($exception);
				$response[$id] = (object)[
					'error' => (object)[
						'message' => 'General (unhandled) RPC error',
						'code' => 500,
					],
				];
			}
		}
		return [
			'bulk' => $response,
		];
	}
}