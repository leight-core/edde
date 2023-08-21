<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

use DateTime;
use Edde\Utils\Mapper\JsonInputMapper;
use Edde\Utils\Mapper\JsonOutputMapper;

interface JobCreateSchema {
	function service(): string;

	function started(): DateTime;

	function request(
		$input = JsonInputMapper::class,
		$output = JsonOutputMapper::class
	);

	function requestSchema(): ?string;
}
