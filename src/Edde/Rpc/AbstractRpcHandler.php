<?php
declare(strict_types=1);

namespace Edde\Rpc;

use Edde\Rpc\Service\IRpcHandler;

abstract class AbstractRpcHandler implements IRpcHandler {
	protected $requestSchema = null;
	protected $responseSchema = null;

	function getRequestSchema(): ?string {
		return $this->requestSchema;
	}

	function getResponseSchema(): ?string {
		return $this->responseSchema;
	}
}
