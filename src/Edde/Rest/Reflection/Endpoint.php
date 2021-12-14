<?php
declare(strict_types=1);

namespace Edde\Rest\Reflection;

use Edde\Dto\AbstractDto;
use Edde\Reflection\Dto\ClassDto;
use Edde\Reflection\Dto\Method\MethodDto;

class Endpoint extends AbstractDto {
	/** @var ClassDto */
	public $class;
	/** @var MethodDto */
	public $method;
	/** @var string[] */
	public $query;
	/** @var string[] */
	public $roles;
	/** @var string */
	public $link;

	public function is(string $interface): bool {
		return $this->class->is($interface);
	}
}
