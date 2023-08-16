<?php
declare(strict_types=1);

namespace Edde\Doctrine\Schema;

interface UuidSchema {
	public function id(): string;
}
