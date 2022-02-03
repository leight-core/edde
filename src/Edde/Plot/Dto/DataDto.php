<?php
declare(strict_types=1);

namespace Edde\Plot\Dto;

use Edde\Dto\AbstractDto;

class DataDto extends AbstractDto {
	/** @var string */
	public $column;
	/** @var mixed */
	public $value;
	/** @var string|null|void */
	public $group;
	/** @var int */
	public $count;
}
