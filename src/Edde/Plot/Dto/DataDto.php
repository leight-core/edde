<?php
declare(strict_types=1);

namespace Edde\Plot\Dto;

use Edde\Dto\AbstractDto;

class DataDto extends AbstractDto {
	/** @var mixed */
	public $column;
	/** @var mixed */
	public $value;
	/** @var mixed|null|void */
	public $group;
}
