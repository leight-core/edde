<?php
declare(strict_types=1);

namespace Edde\Dto;

use function array_map;

abstract class AbstractDtoService implements IDtoService {
	public function fromArray(string $class, ?array $source, bool $allowNull = false) {
		return $source ? $this->fromObject($class, (object)$source, $allowNull) : null;
	}

	public function fromArrays(string $class, array $sources): array {
		return array_map(function (array $source) use ($class) {
			return $this->fromArray($class, $source);
		}, $sources);
	}

	public function fromObjects(string $class, array $objects): array {
		return array_map(function (object $source) use ($class) {
			return $this->fromObject($class, $source);
		}, $objects);
	}
}
