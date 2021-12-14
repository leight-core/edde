<?php
declare(strict_types=1);

namespace Edde\Log\Dto;

use Edde\Repository\Dto\AbstractOrderByDto;

class LogOrderByDto extends AbstractOrderByDto {
	/** @var bool|void */
	public $stamp;
}
