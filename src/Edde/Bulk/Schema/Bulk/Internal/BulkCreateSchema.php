<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Internal;

interface BulkCreateSchema {
	function name(): string;

	function status(): int;

	function commit(): bool;

	function created(): string;

	function userId(): string;
}
