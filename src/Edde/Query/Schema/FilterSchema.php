<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

abstract class FilterSchema {
	abstract public function id(): ?string;

	abstract public function fulltext(): ?string;
}
