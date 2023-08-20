<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

use DateTime;
use Edde\Database\Schema\UuidSchema;
use Edde\Dto\Mapper\ProxyDtoMapper;
use Edde\Dto\Mapper\SchemaDtoMapper;
use Edde\Dto\SmartDto;
use Edde\Job\Mapper\JobCountMapper;
use Edde\Job\Progress\JobProgressFactory;
use Edde\Progress\IProgress;
use Edde\Utils\Mapper\JsonInputMapper;
use Edde\Utils\Mapper\JsonOutputMapper;

interface JobSchema extends UuidSchema {
	const meta = [
		'import' => [
			'JobSchema'       => '@leight/job',
			'type IJobSchema' => '@leight/job',
			'type IJob'       => '@leight/job',
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
		$input = JsonInputMapper::class,
		$output = JsonOutputMapper::class
	): ?string;

	function requestSchema(): ?string;

	function response(
		$input = JsonInputMapper::class,
		$output = JsonOutputMapper::class
	): ?string;

	function responseSchema(): ?string;

	function started(): DateTime;

	function finished(): ?DateTime;

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
