<?php
declare(strict_types=1);

namespace Edde\Phinx\Dto;

use Edde\Dto\AbstractDto;

class UpgradeDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $id;
	/** @var string */
	public $version;
	/** @var string */
	public $name;
	/** @var bool */
	public $active;
}
