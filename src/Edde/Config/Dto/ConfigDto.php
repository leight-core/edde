<?php
declare(strict_types=1);

namespace Edde\Config\Dto;

use Edde\Dto\AbstractDto;

class ConfigDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var string
	 */
	public $key;
	/**
	 * @var mixed|null
	 */
	public $value;
}
