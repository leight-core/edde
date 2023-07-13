<?php
declare(strict_types=1);

namespace Edde\Rpc;

use Edde\Dto\SmartDto;
use Edde\Rest\Endpoint\AbstractSmartEndpoint;
use Edde\Rpc\Schema\RpcBulkRequestSchema;
use Edde\Rpc\Service\RpcServiceTrait;

class RpcEndpoint extends AbstractSmartEndpoint {
	use RpcServiceTrait;

	protected $schema = [
		'post' => RpcBulkRequestSchema::class,
	];

	public function post(SmartDto $dto) {
		return $this->rpcService->execute($dto);
	}
}
