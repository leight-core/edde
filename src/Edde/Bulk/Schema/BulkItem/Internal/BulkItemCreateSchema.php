<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Internal;

use Edde\Date\Mapper\IsoDateMapper;

interface BulkItemCreateSchema {
	function bulkId(): string;

	function created($output = IsoDateMapper::class): string;

	function status(): int;

	function request(): ?string;

	function response(): ?string;

	function userId(): string;
}
