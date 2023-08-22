<?php
declare(strict_types=1);

namespace Edde\Rpc;

use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Query\Schema\WithIdentitySchema;
use Edde\Rpc\Service\IRpcHandler;

abstract class AbstractRpcHandler implements IRpcHandler {
	use SmartServiceTrait;

	/**
	 * Request schema used to call this handler
	 *
	 * @var string|null
	 */
	protected $requestSchema = null;
	/**
	 * Flag used for SDK generator to tell if request schema is optional
	 *
	 * @var bool
	 */
	protected $requestSchemaOptional = false;
	/**
	 * Response schema used by this handler
	 *
	 * @var string|null
	 */
	protected $responseSchema = null;
	/**
	 * Flag used for SDK generator to tell if the response schema is optional
	 *
	 * @var bool
	 */
	protected $responseSchemaOptional = false;
	protected $responseSchemaArray = false;
	/**
	 * If Handler is using a form, this schema is used as ValuesSchema for the form
	 *
	 * @var string|null
	 */
	protected $valuesSchema = null;
	/**
	 * If Handler is as a Query, this schema specifies schema used for filtering
	 *
	 * @var string|null
	 */
	protected $filterSchema = null;
	protected $orderBySchema = null;
	protected $invalidators = [];
	protected $isMutator = false;
	protected $isFetch = false;
	protected $isQuery = false;
	protected $isFindBy = false;
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
			->withValuesSchema($this->withForm ? ($this->valuesSchema ?? $this->requestSchema) : null)
			->withFetch($this->isFetch || $this->is('Fetch'))
			->withFindBy(($this->isFindBy || $this->is('FindBy')) && ($this->filterSchema || $this->orderBySchema))
			->withInvalidators($this->invalidators)
			->withQuery(($this->isQuery || $this->is('Query')) && ($this->filterSchema || $this->orderBySchema))
			->withForm($this->withForm);
	}

	public function getRequestMeta(): RpcWireMeta {
		return new RpcWireMeta(
			$this->isFetch ? WithIdentitySchema::class : $this->requestSchema,
			$this->isFetch ? false : $this->requestSchemaOptional,
			false
		);
	}

	public function getResponseMeta(): RpcWireMeta {
		return new RpcWireMeta(
			$this->responseSchema,
			$this->isFetch ? false : $this->responseSchemaOptional,
			$this->isFetch ?
				false : (
			$this->isQuery ?
				true :
				$this->responseSchemaArray
			)
		);
	}

	public function toDto($response): ?SmartDto {
		if (!$response || !$this->responseSchema) {
			return null;
		}
		return $this->smartService->from($response, $this->responseSchema);
	}

	protected function is(string $type): bool {
		return strpos($this->getName(), $type) !== false;
	}
}
