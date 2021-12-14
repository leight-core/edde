<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type\Utils;

interface IClassType {
	public function class(): string;

	public function className(): string;

	public function namespace(): string;

	public function module(): string;
}
