<?php
declare(strict_types=1);

namespace Edde\Job\Schema\JobLock;

use Edde\Database\Schema\UuidSchema;
use Edde\Dto\Mapper\ImportMapper;
use Edde\Dto\Mapper\ITypeMapper;

interface JobLockSchema extends UuidSchema {
	const meta = [
		ImportMapper::META => [
			'job_id' => ImportMapper::CONVERT_CAMEL,
		],
	];

	function jobId(): string;

	function name(): string;

	function stamp($type = ITypeMapper::TYPE_ISO_DATETIME);

	function active(): bool;
}
