<?php
declare(strict_types=1);

namespace Edde\Job\Dto\Log;

use Edde\Dto\AbstractDto;

class LogTypeDto extends AbstractDto {
	/** @var string */
	public $id;
	/** @var string|void */
	public $type;
}
