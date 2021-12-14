<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type\Utils;

trait ScalarTypeTrait {
	/** @var string */
	public $type;

	public function type(): string {
		return $this->type;
	}
}
