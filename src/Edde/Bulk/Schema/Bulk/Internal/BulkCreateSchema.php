<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Internal;

interface BulkCreateSchema extends \Edde\Bulk\Schema\Bulk\BulkCreateSchema {
	function status(): int;

	function commit(): bool;

	function created(): string;

	function userId(): string;
}
