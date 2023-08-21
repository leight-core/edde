<?php
declare(strict_types=1);

namespace Edde\Rpc\Exception;

use Throwable;

class WithPathException extends RpcException {
	/**
	 * @var array
	 */
	protected $paths;

	public function __construct(array $paths, $message, $code = 0, Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
		$this->paths = $paths;
	}

	public function getPaths(): array {
		return $this->paths;
	}
}
