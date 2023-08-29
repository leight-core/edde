<?php
declare(strict_types=1);

namespace Edde\File\Schema\Query;

use Edde\Query\Schema\FilterSchema;

interface FileFilterSchema extends FilterSchema {
	function native(): ?string;
}
