<?php
declare(strict_types=1);

namespace Edde\Phinx\Dto;

use Edde\Repository\Dto\AbstractOrderByDto;

class UpgradeOrderByDto extends AbstractOrderByDto {
	/** @var bool|void */
	public $name;
	/** @var bool|void */
	public $version;
	/** @var bool|void */
	public $active;
}
