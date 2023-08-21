<?php
declare(strict_types=1);

namespace Edde\Job\Schema\JobLock\Internal;

use Edde\Database\Schema\UuidGeneratorSchema;
use Edde\Dto\Mapper\ExportMapper;
use Edde\Dto\Mapper\ITypeMapper;

interface JobLockCreateSchema extends UuidGeneratorSchema {
	const meta = [
		ExportMapper::META => [
			'jobId' => ExportMapper::CONVERT_SNAKE,
		],
	];

	function jobId(): string;

	function name(): string;

	function stamp($type = ITypeMapper::TYPE_ISO_DATETIME);

	function active(): bool;
}
