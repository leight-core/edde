<?php
declare(strict_types=1);

namespace Edde\Job\Schema\JobLog;

use Edde\Database\Schema\UuidSchema;
use Edde\Dto\Mapper\ImportMapper;
use Edde\Dto\Mapper\ITypeMapper;

interface JobLogSchema extends UuidSchema {
	const meta = [
		ImportMapper::META => [
			'job_id' => ImportMapper::CONVERT_CAMEL,
		],
	];

	function jobId(): string;

	function level(): int;

	function message(): string;

	function item(): ?string;

	function stamp($type = ITypeMapper::TYPE_ISO_DATETIME);

	function reference(): ?string;

	function type(): ?string;
}
