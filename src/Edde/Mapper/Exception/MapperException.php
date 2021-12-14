<?php
declare(strict_types=1);

namespace Edde\Mapper\Exception;

use Edde\EddeException;
use Throwable;

class MapperException extends EddeException {
	/** @var array */
	protected $extra;

	public function __construct($message = '', array $extra = [], Throwable $previous = null) {
		parent::__construct($message, 0, $previous);
		$this->extra = $extra;
	}

	public function getExtra(): array {
		return $this->extra;
	}
}
