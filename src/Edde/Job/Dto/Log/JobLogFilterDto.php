<?php
declare(strict_types=1);

namespace Edde\Job\Dto\Log;

use Edde\Repository\Dto\AbstractFilterDto;

class JobLogFilterDto extends AbstractFilterDto {
	/** @var string|void */
	public $jobId;
	/** @var int[]|void */
	public $level;
	/** @var string[]|void */
	public $type;
	/** @var string[]|void */
	public $notType;
}
