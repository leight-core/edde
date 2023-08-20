<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Internal;

use DateTime;

interface BulkItemCreateSchema extends \Edde\Bulk\Schema\BulkItem\BulkItemCreateSchema {
	function created(): DateTime;

	function status(): int;

	function userId(): string;
}
