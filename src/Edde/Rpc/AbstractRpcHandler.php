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
	protected $filterSchema = null;
	protected $orderBySchema = null;
	protected $invalidators = [];
	protected $isMutator = false;
	protected $isFetch = false;
	protected $isQuery = false;
	protected $withForm = false;
	protected $meta;

	public function getName(): string {
		return str_replace(
			['RpcHandler'],
			'',
			substr(strrchr(get_class($this), '\\'), 1)
		);
	}

	public function getMeta(): RpcHandlerMeta {
		return $this->meta ?: $this->meta = (new RpcHandlerMeta(
			$this->getRequestMeta(),
			$this->getResponseMeta()
		))
			->withFilterSchema($this->filterSchema)
			->withOrderBySchema($this->orderBySchema)
			->withMutator($this->isMutator || $this->withForm)
			->withFetch($this->isFetch)
			->withInvalidators($this->invalidators)
			->withQuery($this->isQuery && ($this->filterSchema || $this->orderBySchema))
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
		return $this->smartService->from($response, $this->responseSchema);
	}
}
