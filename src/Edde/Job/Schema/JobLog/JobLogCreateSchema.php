<?php
declare(strict_types=1);

namespace Edde\Job\Schema\JobLog;

use Edde\Database\Schema\UuidGeneratorSchema;
use Edde\Dto\Mapper\ExportMapper;
use Edde\Dto\Mapper\ITypeMapper;

interface JobLogCreateSchema extends UuidGeneratorSchema {
	const meta = [
		ExportMapper::META => [
			'jobId' => ExportMapper::CONVERT_SNAKE,
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
