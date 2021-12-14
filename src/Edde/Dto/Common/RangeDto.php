<?php
declare(strict_types=1);

namespace Edde\Repository\Dto;

use Edde\Dto\AbstractDto;

class RangeDto extends AbstractDto {
	/** @var mixed|void */
	public $from;
	/** @var mixed|void */
	public $to;
}
