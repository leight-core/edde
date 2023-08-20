<?php
declare(strict_types=1);

namespace Edde\Database\Schema;

interface IdSchema {
	public function id(): int;
}
