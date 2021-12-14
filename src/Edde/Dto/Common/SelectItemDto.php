<?php
declare(strict_types=1);

namespace Edde\Dto\Common;

use Edde\Dto\AbstractDto;

class SelectItemDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var string
	 */
	public $code;
}
