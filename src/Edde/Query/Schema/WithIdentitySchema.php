<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

abstract class WithIdentitySchema {
	abstract public function id(): string;
}
