<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

use Edde\Dto\Mapper\ITypeMapper;

interface BulkItemCreateSchema {
	const meta = [
		'import' => [
            'type IBulkItemCreate'       => '@pico/bulk',
            'type IBulkItemCreateSchema' => '@pico/bulk',
            'BulkItemCreateSchema'       => '@pico/bulk',
		],
	];

	function bulkId(): string;

	function service(): string;

	function request(
		$type = ITypeMapper::TYPE_JSON
	);
}
