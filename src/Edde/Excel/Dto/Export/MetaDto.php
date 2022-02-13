<?php
declare(strict_types=1);

namespace Edde\Excel\Dto\Export;

use Edde\Dto\AbstractDto;

class MetaDto extends AbstractDto {
	/**
	 * @var TabDto[]
	 */
	public $tabs;
}
