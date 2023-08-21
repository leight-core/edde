<?php
declare(strict_types=1);

namespace Edde\Job\Schema\JobLock;

use Edde\Database\Schema\UuidSchema;
use Edde\Dto\Mapper\ITypeMapper;

interface JobLockSchema extends UuidSchema {
	function jobId(): string;

	function name(): string;

	function stamp($type = ITypeMapper::TYPE_ISO_DATETIME);

	function active(): bool;
}
