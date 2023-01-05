<?php
declare(strict_types=1);

namespace Edde\Config\Dto\Create;

use Edde\Dto\AbstractDto;

class ConfigDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $key;
	/**
	 * @var mixed|null|void
	 */
	public $value;
	/**
	 * @var bool
	 */
	public $private;
}
