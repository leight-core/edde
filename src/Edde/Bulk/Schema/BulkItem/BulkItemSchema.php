<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Bulk\Service\BulkService;
use Edde\Database\Schema\UuidSchema;
use Edde\Dto\Mapper\ExportMapper;
use Edde\Dto\Mapper\ImportMapper;
use Edde\Dto\Mapper\ITypeMapper;
use Edde\Dto\Mapper\ProxyDtoMapper;

interface BulkItemSchema extends UuidSchema {
	const meta = [
		'import'           => [
            'type IBulkItem'       => '@pico/bulk',
            'type IBulkItemSchema' => '@pico/bulk',
            'BulkItemSchema'       => '@pico/bulk',
		],
		ExportMapper::META => [
			'bulkId' => ExportMapper::CONVERT_SNAKE,
			'userId' => ExportMapper::CONVERT_SNAKE,
		],
		ImportMapper::META => [
			'user_id' => ImportMapper::CONVERT_CAMEL,
			'bulk_id' => ImportMapper::CONVERT_CAMEL,
		],
	];

	function bulkId(): string;

	function bulk(
		$load = true,
		$output = ProxyDtoMapper::class,
		$meta = [
			'source' => 'bulkId',
			'proxy'  => [
				BulkService::class,
				'find',
			],
		]
	): BulkSchema;

	function service(): string;

	function created(
		$type = ITypeMapper::TYPE_ISO_DATETIME
	): string;

	function status(): int;

	function request(
		$type = ITypeMapper::TYPE_JSON
	): ?string;

	function response(
		$type = ITypeMapper::TYPE_JSON
	): ?string;

	function userId(): string;
}
