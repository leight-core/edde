<?php
declare(strict_types=1);

namespace Edde\Job\Dto\Interrupt;

use Edde\Dto\AbstractDto;

class InterruptDto extends AbstractDto {
	/** @var string|void */
	public $jobId;
	/** @var string|void */
	public $userId;
}
