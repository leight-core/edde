<?php
declare(strict_types=1);

namespace Edde\Job\Dto\Delete;

use Edde\Repository\Dto\AbstractFilterDto;

class DeleteDto extends AbstractFilterDto {
	/** @var string|void */
	public $jobId;
	/** @var string|void */
	public $userId;
	/** @var string[]|void */
	public $services;
}
