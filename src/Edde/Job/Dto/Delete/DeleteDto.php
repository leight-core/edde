<?php
declare(strict_types=1);

namespace Edde\Job\Dto\Delete;

use Edde\Dto\AbstractDto;

class DeleteDto extends AbstractDto {
	/** @var string|void */
	public $jobId;
	/** @var string|void */
	public $userId;
	/** @var string[]|void */
	public $services;
}
