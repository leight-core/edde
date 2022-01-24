<?php
declare(strict_types=1);

namespace Edde\Log;

use Edde\Uuid\UuidServiceTrait;

class TraceService {
	use UuidServiceTrait;

	/** @var string */
	protected $trace;
	/** @var string|null */
	protected $reference;

	public function __construct(string $trace = null) {
		$this->trace = $trace;
	}

	/**
	 * Return current trace ID of the request (regardless of HTTP or being a CLI call).
	 *
	 * @return string
	 */
	public function trace(): string {
		return $this->trace = $this->trace ?? $this->uuidService->uuid4();
	}

	/**
	 * Returns current reference (parent trace id); should be set before use as it defaults to null.
	 *
	 * So for example when calls between microservices happens, one is responsible for reading reference from
	 * http header and setting it to this service.
	 *
	 * @return string|null
	 */
	public function reference(): ?string {
		return $this->reference;
	}

	/**
	 * Set (or unset) reference trace ID (for example when HTTP request calls CLI, it should also
	 * pass it's reference id to keep track of the tree of events).
	 *
	 * @param string|null $reference
	 */
	public function setReference(?string $reference): void {
		$this->reference = $reference;
	}
}
