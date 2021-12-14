<?php
declare(strict_types=1);

namespace Edde\Profiler\Dto;

use Edde\Repository\Dto\AbstractFilterDto;

class ProfilerFilterDto extends AbstractFilterDto {
	/**
	 * @var string|void
	 * @description name like
	 */
	public $name;
	/**
	 * @var string[]|void
	 * @description names in (exact match)
	 */
	public $names;
}
