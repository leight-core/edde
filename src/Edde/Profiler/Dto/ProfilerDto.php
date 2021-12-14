<?php
declare(strict_types=1);

namespace Edde\Profiler\Dto;

use Edde\Dto\AbstractDto;

class ProfilerDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var double
	 */
	public $stamp;
	/**
	 * @var double
	 */
	public $runtime;
}
