<?php
declare(strict_types=1);

namespace Edde\Rpc;

/**
 * Meta information related to "wire" - request/response.
 */
class RpcWireMeta {
	/**
	 * @var string|null
	 */
	protected $schema;
	/**
	 * @var bool
	 */
	protected $isOptional;
	/**
	 * @var bool
	 */
	protected $isArray;

	/**
	 * @param string|null $schema
	 * @param bool        $isOptional
	 * @param bool        $isArray
	 */
	public function __construct(?string $schema, bool $isOptional, bool $isArray) {
		$this->schema = $schema;
		$this->isOptional = $isOptional;
		$this->isArray = $isArray;
	}

	public function getSchema(): ?string {
		return $this->schema;
	}

	public function isOptional(): bool {
		return $this->isOptional;
	}

	public function isArray(): bool {
		return $this->isArray;
	}
}
