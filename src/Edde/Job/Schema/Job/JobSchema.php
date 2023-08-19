<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

use DateTime;
use Edde\Doctrine\Schema\UuidSchema;
use Edde\Dto\Mapper\ProxyDtoMapper;
use Edde\Job\Progress\JobProgressFactory;
use Edde\Progress\IProgress;

interface JobSchema extends UuidSchema {
	const meta = [
		'import' => [
			'JobSchema'  => '@leight/job',
			'IJobSchema' => '@leight/job',
			'IJob'       => '@leight/job',
		],
	];

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

	function withProgress(
		$output = ProxyDtoMapper::class,
		$meta = [
			'source' => 'id',
			'proxy'  => [
				JobProgressFactory::class,
				'create',
			],
		]
	): IProgress;
}
