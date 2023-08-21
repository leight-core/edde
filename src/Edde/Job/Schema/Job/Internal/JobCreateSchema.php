<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job\Internal;

use Edde\Dto\Mapper\ExportMapper;
use Edde\Dto\Mapper\ScalarMapper;
use Edde\Utils\Mapper\JsonInputMapper;

interface JobCreateSchema {
	const meta = [
		ExportMapper::META => [
			'userId' => ExportMapper::CONVERT_SNAKE,
		],
	];

	function service(): string;

	function status(): int;

	function total(): int;

	function progress(): float;

	function successCount(): int;

	function errorCount(): int;

	function skipCount(): int;

	function started($type = ScalarMapper::TYPE_ISO_DATETIME): string;

	function request(
		$input = JsonInputMapper::class
	): ?string;

	function requestSchema(): string;

	function userId(): string;
}
