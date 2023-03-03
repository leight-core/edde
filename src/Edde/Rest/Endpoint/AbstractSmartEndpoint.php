<?php
declare(strict_types=1);

namespace Edde\Rest\Endpoint;

use Edde\Reflection\Dto\Method\IRequestMethod;
use Edde\Rest\Exception\RestException;

abstract class AbstractSmartEndpoint extends AbstractEndpoint {
	protected $schema = [];

	protected function createRequest(IRequestMethod $requestMethod) {
		if (!($schema = $this->schema[$this->endpoint->method->name] ?? null)) {
			throw new RestException(sprintf("Missing schema for method [%s::%s].", static::class, $this->endpoint->method->name), 400);
		}
		return $this->smartService->from($this->request->getParsedBody(), $schema)->validate();
	}
}
