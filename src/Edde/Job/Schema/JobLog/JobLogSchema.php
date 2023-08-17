<?php
declare(strict_types=1);

namespace Edde\Job\Schema\JobLog;

use DateTime;
use Edde\Doctrine\Schema\UuidSchema;

interface JobLogSchema extends UuidSchema {
	function jobId(): string;

	function level(): int;

	function message(): string;

	function item(): ?string;

	function stamp(): DateTime;

	function reference(): ?string;

	function type(): ?string;
}