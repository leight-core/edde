<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type\Utils;

use Edde\Reflection\Dto\Type\AbstractType;

trait GenericTypeTrait {
	/** @var AbstractType */
	public $type;
	/** @var AbstractType[] */
	public $generics;

	public function type(): AbstractType {
		return $this->type;
	}

	public function generics(): array {
		return $this->generics;
	}
}
