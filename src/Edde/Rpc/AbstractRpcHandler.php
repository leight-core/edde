<?php
declare(strict_types=1);

namespace Edde\Rpc;

use Edde\Rpc\Service\IRpcHandler;

abstract class AbstractRpcHandler implements IRpcHandler {
	protected $requestSchema = null;
	protected $responseSchema = null;

	public function getName(): string {
		return substr(strrchr(get_class($this), '\\'), 1);
	}

	public function getRequestSchema(): ?string {
		return $this->requestSchema;
	}

	public function getResponseSchema(): ?string {
		return $this->responseSchema;
	}
}
