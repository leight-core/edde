<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

use DateTime;
use Edde\Doctrine\Schema\UuidSchema;

interface JobSchema extends UuidSchema {
	function service(): string;

	function status(): int;

	function total(): int;

	function progress(): float;

	function successCount(): int;

	function errorCount(): int;

	function skipCount(): int;

	function request(): ?string;

	function requestSchema(): ?string;

	function response(): ?string;

	function responseSchema(): ?string;

	function started(): DateTime;

	function finished(): ?DateTime;

	function userId(): string;
}
