<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

use DateTime;

interface JobUpdateSchema extends JobCreateSchema {
	const partial = true;

	function status(): int;

	function total(): int;

	function progress(): float;

	function successCount(): int;

	function errorCount(): int;

	function skipCount(): int;

	function request(): string;

	function requestSchema(): string;

	function response(): string;

	function responseSchema(): string;

	function finished(): ?DateTime;
}
