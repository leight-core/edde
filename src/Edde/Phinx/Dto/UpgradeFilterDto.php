<?php
declare(strict_types=1);

namespace Edde\Phinx\Dto;

use Edde\Repository\Dto\AbstractFilterDto;

class UpgradeFilterDto extends AbstractFilterDto {
	/** @var bool|void */
	public $active;
}
