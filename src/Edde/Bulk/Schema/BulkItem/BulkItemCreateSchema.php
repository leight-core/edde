<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

use Edde\Dto\Mapper\ITypeMapper;

interface BulkItemCreateSchema {
	const meta = [
		'import' => [
			'type IBulkItemCreate'       => '@leight/bulk',
			'type IBulkItemCreateSchema' => '@leight/bulk',
			'BulkItemCreateSchema'       => '@leight/bulk',
		],
	];

	function bulkId(): string;

	function service(): string;

	function request(
		$type = ITypeMapper::TYPE_JSON
	);
}
