<?php
declare(strict_types=1);

namespace Edde\Bulk\Job;

use Edde\Bulk\Exception\BulkImportException;
use Edde\Bulk\Schema\BulkItem\BulkItemStatus;
use Edde\Bulk\Schema\BulkItem\Internal\BulkItemUpdateRequestSchema;
use Edde\Bulk\Schema\BulkItem\Query\BulkItemQuerySchema;
use Edde\Bulk\Service\BulkItemServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Job\Async\AbstractAsyncService;
use Edde\Progress\IProgress;
use Edde\Query\Schema\WithIdentitySchema;
use Edde\Rpc\Schema\RpcBulkRequestSchema;
use Edde\Rpc\Service\RpcServiceTrait;
use Throwable;

class BulkImportAsyncService extends AbstractAsyncService {
	use BulkItemServiceTrait;
	use RpcServiceTrait;

	protected function handle(SmartDto $job, IProgress $progress, ?SmartDto $request) {
		if (!$request) {
			throw new BulkImportException(sprintf('Cannot start bulk import (job [%s]), missing bulk ID (schema: [%s]).', $job->getValue('id'), WithIdentitySchema::class));
		}
		$query = $this->smartService->from(
			[
				'filter' => [
					'bulkId' => $request->getValue('id'),
				],
			],
			BulkItemQuerySchema::class
		);
		$progress->onStart(
			$total = $this->bulkItemService->total($query)
		);
		$size = 25;
		$pages = ceil($total / $size);
		for ($page = 0; $page < $pages; $page++) {
			foreach ($this->bulkItemService->query(
				$query->merge([
					'cursor' => [
						'page' => $page,
						'size' => $size,
					],
				])
			) as $bulkItem) {
				sleep(5);
				try {
					$response = $this->rpcService->execute(
						$this->smartService->from(
							[
								'bulk' => [
									$bulkItem->getValue('id') => [
										'service' => $bulkItem->getValue('service'),
										'data'    => $bulkItem->getValue('request'),
									],
								],
							],
							RpcBulkRequestSchema::class
						)
					);
					$base = $response['bulk'][$bulkItem->getValue('id')];
					$this->bulkItemService->upsert(
						$this->smartService->from(
							[
								'update' => [
									'response' => $base['data'] ?? $base['error'],
									'status'   => isset($base['error']) ? BulkItemStatus::ERROR : BulkItemStatus::SUCCESS,
								],
								'filter' => [
									'id' => $bulkItem->getValue('id'),
								],
							],
							BulkItemUpdateRequestSchema::class
						)
					);
					$progress->onProgress();
				} catch (Throwable $throwable) {
					$progress->onError($throwable);
				}
			}
		}
	}
}
