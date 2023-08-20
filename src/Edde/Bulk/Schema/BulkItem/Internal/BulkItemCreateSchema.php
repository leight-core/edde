<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\BulkItem\Internal;

use DateTime;
use Edde\Utils\Mapper\JsonInputMapper;
use Edde\Utils\Mapper\JsonOutputMapper;

interface BulkItemCreateSchema {
	function bulkId(): string;

	function service(): string;

	function created(): DateTime;

	function status(): int;

	function request(
		$input = JsonInputMapper::class,
		$output = JsonOutputMapper::class
	);

	function response(
		$input = JsonInputMapper::class,
		$output = JsonOutputMapper::class
	);

	function userId(): string;
}
