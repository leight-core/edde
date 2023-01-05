<?php
declare(strict_types=1);

namespace Edde\Config\Dto\Patch;

use Edde\Dto\AbstractDto;

class ConfigDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $key;
	/**
	 * @var mixed|void
	 */
	public $value;
	/**
	 * @var bool|void
	 */
	public $private;
}
