<?php
declare(strict_types=1);

namespace Edde\Rpc;

use Edde\Rpc\Service\IRpcHandler;

abstract class AbstractRpcHandler implements IRpcHandler {
	protected $requestSchema = null;
	protected $requestSchemaOptional = false;
	protected $responseSchema = null;
	protected $responseSchemaOptional = false;
	protected $isMutator = false;

	public function getName(): string {
		return substr(strrchr(get_class($this), '\\'), 1);
	}

	public function isMutator(): bool {
		return $this->isMutator;
	}


	public function getRequestSchema(): ?string {
		return $this->requestSchema;
	}

	public function getResponseSchema(): ?string {
		return $this->responseSchema;
	}

	public function isRequestSchemaOptional(): bool {
		return $this->requestSchemaOptional;
	}

	public function isResponseSchemaOptional(): bool {
		return $this->responseSchemaOptional;
	}
}
