<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Reflection\Dto\ClassDto;
use function str_replace;

class NameResolver {
	public function toExport(string $name): string {
		return str_replace(["Endpoint"], "", $name);
	}

	public function toAlias(ClassDto $classDto): string {
		$name = $classDto->name;
		$hash = hash('sha256', $classDto->fqdn);
		return $name . '_' . substr($hash, 0, 6);
	}
}
