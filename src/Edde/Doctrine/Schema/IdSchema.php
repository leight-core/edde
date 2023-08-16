<?php
declare(strict_types=1);

namespace Edde\Doctrine\Schema;

interface IdSchema {
	public function id(): int;
}
