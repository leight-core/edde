<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

use Edde\Database\Schema\UuidSchema;
use Edde\Dto\Mapper\ImportMapper;
use Edde\Schema\ISchemaLoader;

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

	function commit($type = ISchemaLoader::TYPE_BOOLINT): bool;

	function created($type = ISchemaLoader::TYPE_ISO_DATETIME): string;

	function userId(): string;
}
