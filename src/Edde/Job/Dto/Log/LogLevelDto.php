<?php
declare(strict_types=1);

namespace Edde\Job\Dto\Log;

use Edde\Dto\AbstractDto;

class LogLevelDto extends AbstractDto {
	/** @var int */
	public $level;
	/** @var string */
	public $label;
}
