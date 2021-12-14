<?php
declare(strict_types=1);

namespace Edde\Mapper\Exception;

use Throwable;

class ItemException extends MapperException {
	/** @var string|null */
	protected $type;

	public function __construct($message, array $extra = [], string $type = null, Throwable $previous = null) {
		parent::__construct($message, $extra, $previous);
		$this->type = $type;
	}

	public function getType(): ?string {
		return $this->type;
	}
}
