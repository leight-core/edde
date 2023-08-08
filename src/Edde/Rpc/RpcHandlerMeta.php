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
}
