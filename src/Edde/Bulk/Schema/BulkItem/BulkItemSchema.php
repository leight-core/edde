<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem;

use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Bulk\Service\BulkService;
use Edde\Date\Mapper\IsoDateMapper;
use Edde\Doctrine\Schema\UuidSchema;
use Edde\Dto\Mapper\ProxyDtoMapper;
use Edde\Utils\Mapper\JsonInputMapper;
use Edde\Utils\Mapper\JsonOutputMapper;

interface BulkItemSchema extends UuidSchema {
	const STATUS_PENDING = 0;
	const STATUS_SUCCESS = 1;
	const STATUS_ERROR = 2;
	const STATUS_SETTLED = 3;

	const meta = [
		'import' => [
			'type IBulkItem'       => '@leight/bulk',
			'type IBulkItemSchema' => '@leight/bulk',
			'BulkItemSchema'       => '@leight/bulk',
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
				'get',
			],
		]
	): BulkSchema;

	function created($output = IsoDateMapper::class): string;

	function status(): int;

	function request(
		$input = JsonInputMapper::class,
		$output = JsonOutputMapper::class
	): ?string;

	function response(
		$input = JsonInputMapper::class,
		$output = JsonOutputMapper::class
	): ?string;

	function userId(): string;
}
