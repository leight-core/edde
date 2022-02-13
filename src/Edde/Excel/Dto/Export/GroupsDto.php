<?php
declare(strict_types=1);

namespace Edde\Excel\Dto\Export;

use Edde\Dto\AbstractDto;

class GroupsDto extends AbstractDto {
	/**
	 * @var GroupDto[]
	 */
	public $groups = [];
}
