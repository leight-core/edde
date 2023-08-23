<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

use Edde\Database\Schema\UuidSchema;
use Edde\Dto\Mapper\ImportMapper;
use Edde\Dto\Mapper\ITypeMapper;
use Edde\Dto\Mapper\ProxyDtoMapper;
use Edde\Dto\Mapper\SchemaDtoMapper;
use Edde\Dto\SmartDto;
use Edde\Job\Mapper\JobCountMapper;
use Edde\Job\Progress\JobProgressFactory;
use Edde\Progress\IProgress;

interface JobSchema extends UuidSchema {
	const meta = [
		'import'           => [
            'JobSchema'       => '@pico/job',
            'type IJobSchema' => '@pico/job',
            'type IJob'       => '@pico/job',
		],
		ImportMapper::META => [
			'user_id' => ImportMapper::CONVERT_CAMEL,
		],
	];

	function service(): string;

	function status(): int;

	function total(): int;

	function progress(): float;

	function successCount(): int;

	function errorCount(): int;

	function skipCount(): int;

	function count(
		$output = JobCountMapper::class
	): int;

	function request(
		$type = ITypeMapper::TYPE_JSON
	): ?string;

	function requestSchema(): ?string;

	function response(
		$type = ITypeMapper::TYPE_JSON
	): ?string;

	function responseSchema(): ?string;

	function started(
		$type = ITypeMapper::TYPE_ISO_DATETIME
	): string;

	function finished(
		$type = ITypeMapper::TYPE_ISO_DATETIME
	): ?string;

	function userId(): string;

	function withProgress(
		$output = ProxyDtoMapper::class,
		$meta = [
			'source' => 'id',
			'proxy'  => [
				JobProgressFactory::class,
				'create',
			],
		],
		$internal = true
	): IProgress;

	function withRequest(
		$output = SchemaDtoMapper::class,
		$meta = [
			'source' => 'request',
			'schema' => 'requestSchema',
		],
		$internal = true
	): ?SmartDto;
}
