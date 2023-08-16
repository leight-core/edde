<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Internal;

use DateTime;

interface BulkItemCreateSchema {
	function bulkId(): string;

	function created(): DateTime;

	function status(): int;

	function request(): ?string;

	function response(): ?string;

	function userId(): string;
}
