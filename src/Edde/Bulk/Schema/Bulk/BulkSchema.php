<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

use Edde\Database\Schema\UuidSchema;
use Edde\Date\Mapper\IsoDateMapper;
use Edde\Dto\Mapper\ImportMapper;
use Edde\Utils\Mapper\IntBoolMapper;

interface BulkSchema extends UuidSchema {
	const meta = [
		'import'           => [
			'type IBulk'       => '@leight/bulk',
			'type IBulkSchema' => '@leight/bulk',
			'BulkSchema'       => '@leight/bulk',
		],
		ImportMapper::META => [
			'user_id' => ImportMapper::CONVERT_CAMEL,
		],
	];

	function name(): string;

	function status(): int;

	function commit($output = IntBoolMapper::class): bool;

	function created($output = IsoDateMapper::class): string;

	function userId(): string;
}
