<?php
declare(strict_types=1);

namespace Edde\Log\Schema;

use Edde\Database\Schema\UuidSchema;

interface LogSchema extends UuidSchema {
	function type(): string;

	function log(): string;

	function stack(): ?string;

	function stamp(): string;

	function trace(): ?string;

	function reference(): ?string;

	function microtime(): float;
}
