<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk\Internal;

use Edde\Dto\Mapper\ExportMapper;

interface BulkCreateSchema {
	const meta = [
		ExportMapper::META => [
			'userId' => '$camel',
		],
	];

	function name(): string;

	function status(): int;

	function commit(): bool;

	function created(): string;

	function userId(): string;
}
