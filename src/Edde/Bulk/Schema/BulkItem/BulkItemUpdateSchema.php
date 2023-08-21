<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

use Edde\Dto\Mapper\ITypeMapper;

interface BulkItemUpdateSchema {
	const meta = [
		'import' => [
			'type IBulkItemUpdate'       => '@leight/bulk',
			'type IBulkItemUpdateSchema' => '@leight/bulk',
			'BulkItemUpdateSchema'       => '@leight/bulk',
		],
	];

	const partial = true;

	function bulkId(): string;

	function service(): string;

	function status(): int;

	function request(
		$type = ITypeMapper::TYPE_JSON
	);

	function response(
		$type = ITypeMapper::TYPE_JSON
	): string;
}
