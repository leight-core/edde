<?php
declare(strict_types=1);

namespace Edde\Profiler\Dto;

use Edde\Repository\Dto\AbstractOrderByDto;

class ProfilerOrderByDto extends AbstractOrderByDto {
	/** @var bool|void */
	public $name;
	/** @var bool|void */
	public $stamp;
	/** @var bool|void */
	public $runtime;
}
