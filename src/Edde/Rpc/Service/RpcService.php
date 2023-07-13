<?php
declare(strict_types=1);

namespace Edde\Rpc\Service;

use DI\NotFoundException;
use Edde\Container\ContainerTrait;
use Edde\Dto\SmartDto;
use Edde\Log\LoggerTrait;
use Edde\Rpc\Exception\RpcException;
use Throwable;

class RpcService {
	use ContainerTrait;
	use LoggerTrait;

	public function execute(SmartDto $dto) {
		$response = [];
		/** @var $bulk SmartDto */
		foreach ($dto->get('bulk')->get() as $id => $bulk) {
			$name = $bulk->get('service')->get();
			try {
				$service = $this->container->get($name);
				if (!$service instanceof IRpcHandler) {
					throw new RpcException(sprintf('Requested service [%s] is not a handler.', $name));
				}
				$response[$id] = $service->handle($bulk->get('data')->get());
			} catch (NotFoundException $exception) {
				$this->logger->error($exception);
				$response[$id] = (object)[
					'error' => (object)[
						'message' => sprintf('Unknown RPC service [%s].', $name),
					],
				];
			} catch (RpcException $exception) {
				$this->logger->error($exception);
				$response[$id] = (object)[
					'error' => (object)[
						'message' => $exception->getMessage(),
					],
				];
			} catch (Throwable $exception) {
				$this->logger->error($exception);
				$response[$id] = (object)[
					'error' => (object)[
						'message' => 'General (unhandled) RPC error',
					],
				];
			}
		}
		return [
			'bulk' => $response,
		];
	}
}
