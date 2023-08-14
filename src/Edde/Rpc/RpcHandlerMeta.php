<?php
declare(strict_types=1);

namespace Edde\Rpc;

/**
 * Meta information about a Handler.
 */
class RpcHandlerMeta {
	/**
	 * @var RpcWireMeta
	 */
	protected $requestMeta;
	/**
	 * @var RpcWireMeta
	 */
	protected $responseMeta;
	/**
	 * @var string|null
	 */
	protected $filterSchema;
	/**
	 * @var string|null
	 */
	protected $orderBySchema;
	protected $features = [];

	/**
	 * @param RpcWireMeta $requestMeta
	 * @param RpcWireMeta $responseMeta
	 */
	public function __construct(RpcWireMeta $requestMeta, RpcWireMeta $responseMeta) {
		$this->requestMeta = $requestMeta;
		$this->responseMeta = $responseMeta;
	}

	public function getRequestMeta(): RpcWireMeta {
		return $this->requestMeta;
	}

	public function getResponseMeta(): RpcWireMeta {
		return $this->responseMeta;
	}

	public function withFilterSchema(?string $filterSchema): self {
		$this->filterSchema = $filterSchema;
		return $this;
	}

	public function getFilterSchema(): ?string {
		return $this->filterSchema;
	}

	public function withOrderBySchema(?string $orderBySchema): self {
		$this->orderBySchema = $orderBySchema;
		return $this;
	}

	public function getOrderBySchema(): ?string {
		return $this->orderBySchema;
	}

	public function withMutator(bool $enable): self {
		if (!$enable) {
			return $this;
		}
		$this->features[] = 'mutator';
		return $this;
	}

	public function isMutator(): bool {
		return in_array('mutator', $this->features);
	}

	public function withFetch(bool $enable): self {
		if (!$enable) {
			return $this;
		}
		$this->features[] = 'fetch';
		return $this;
	}

	public function isFetch(): bool {
		return in_array('fetch', $this->features);
	}

	public function withQuery(bool $enable): self {
		if (!$enable) {
			return $this;
		}
		$this->features[] = 'query';
		return $this;
	}

	public function isQuery(): bool {
		return in_array('query', $this->features);
	}

	public function withForm(bool $enable): self {
		if (!$enable) {
			return $this;
		}
		$this->features[] = 'with-form';
		return $this;
	}

	public function isWithForm(): bool {
		return in_array('with-form', $this->features);
	}
}
