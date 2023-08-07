<?php
declare(strict_types=1);

namespace Edde\Rpc;

use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Rpc\Service\IRpcHandler;

abstract class AbstractRpcHandler implements IRpcHandler {
	use SmartServiceTrait;

	protected $requestSchema = null;
	protected $requestSchemaOptional = false;
	protected $responseSchema = null;
	protected $responseSchemaOptional = false;
	protected $responseSchemaArray = false;
	protected $isMutator = false;

	public function getName(): string {
		return substr(strrchr(get_class($this), '\\'), 1);
	}

	public function isMutator(): bool {
		return $this->isMutator;
	}

	public function getRequestMeta(): RpcHandlerMeta {
		return new RpcHandlerMeta(
			$this->requestSchema,
			$this->requestSchemaOptional,
			false
		);
	}

	public function getResponseMeta(): RpcHandlerMeta {
		return new RpcHandlerMeta(
			$this->responseSchema,
			$this->responseSchemaOptional,
			$this->responseSchemaArray
		);
	}

	public function toDto($response): ?SmartDto {
		if (!$response || !$this->responseSchema) {
			return null;
		}
		return $this->smartService->from((object)$response, $this->responseSchema);
	}
}
