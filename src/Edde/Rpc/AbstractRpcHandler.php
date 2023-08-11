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
	protected $isFetch = false;
	protected $withForm = false;
	protected $meta;

	public function getName(): string {
		return str_replace(
			['RpcHandler'],
			'',
			substr(strrchr(get_class($this), '\\'), 1)
		);
	}

	public function isMutator(): bool {
		return $this->isMutator;
	}

	public function getMeta(): RpcHandlerMeta {
		return $this->meta ?: $this->meta = (new RpcHandlerMeta(
			$this->getRequestMeta(),
			$this->getResponseMeta()
		))
			->withMutator($this->isMutator)
			->withFetch($this->isFetch)
			->withForm($this->withForm);
	}

	public function getRequestMeta(): RpcWireMeta {
		return new RpcWireMeta(
			$this->requestSchema,
			$this->requestSchemaOptional,
			false
		);
	}

	public function getResponseMeta(): RpcWireMeta {
		return new RpcWireMeta(
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
