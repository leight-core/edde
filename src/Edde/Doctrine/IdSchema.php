<?php
declare(strict_types=1);

namespace Edde\Doctrine;

interface IdSchema {
	public function id(): int;
}
