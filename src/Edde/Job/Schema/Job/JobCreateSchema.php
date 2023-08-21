<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

use DateTime;
use Edde\Dto\Mapper\ITypeMapper;

interface JobCreateSchema {
	function service(): string;

	function started(): DateTime;

	function request(
		$type = ITypeMapper::TYPE_JSON
	);

	function requestSchema(): ?string;
}
