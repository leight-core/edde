<?php
declare(strict_types=1);

namespace Edde\Rpc\Service;

use DI\DependencyException;
use DI\NotFoundException;
use Edde\Container\ContainerTrait;
use Edde\Database\Exception\RequiredResultException;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Log\LoggerTrait;
use Edde\Rpc\Exception\RpcException;
use Edde\Rpc\Exception\WithPathException;
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
		foreach ($dto->getSafeValue('bulk', []) as $id => $bulk) {
			$name = $bulk->getValue('service');
			try {
				$service = $this->resolve($name);
				$result = $service->handle(
					($requestSchema = $service->getMeta()->getRequestMeta()->getSchema()) ? $this->smartService->from($bulk->getValue('data'), $requestSchema) : SmartDto::ofDummy()
				);
				$response[$id] = [
					'data' => SmartDto::exportOf($result),
				];
			} catch (RequiredResultException $exception) {
				$this->logger->error($exception);
				$response[$id] = [
					'error' => [
						'message' => $exception->getMessage(),
						'code'    => 404,
					],
				];
			} catch (NotFoundException $exception) {
				$this->logger->error($exception);
				$response[$id] = [
					'error' => [
						'message' => sprintf('Unknown RPC service [%s].', $name),
						'code' => $exception->getCode(),
					],
				];
			} catch (WithPathException $exception) {
				$this->logger->error($exception);
				$response[$id] = [
					'error' => [
						'message' => $exception->getMessage(),
						'code'    => $exception->getCode(),
						'paths'   => $exception->getPaths(),
					],
				];
			} catch (RpcException $exception) {
				$this->logger->error($exception);
				$response[$id] = [
					'error' => [
						'message' => $exception->getMessage(),
						'code' => $exception->getCode(),
					],
				];
			} catch (Throwable $exception) {
				$this->logger->error($exception);
				$response[$id] = [
					'error' => [
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
