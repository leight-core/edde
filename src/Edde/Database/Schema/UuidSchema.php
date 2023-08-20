<?php
declare(strict_types=1);

namespace Edde\Database\Schema;

use Edde\Uuid\Mapper\UuidMapper;

interface UuidSchema {
	function id($output = UuidMapper::class): string;
}
