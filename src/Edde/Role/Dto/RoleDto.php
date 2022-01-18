<?php
declare(strict_types=1);

namespace Edde\Role\Dto;

use Edde\Dto\AbstractDto;

class RoleDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var string
	 */
	public $name;
}
