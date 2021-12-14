<?php
declare(strict_types=1);

namespace Edde\Progress\Dto;

use Edde\Dto\AbstractDto;

class ItemDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $index;
	/**
	 * @var mixed|null
	 * @description source row of the excel file (without mapper applied)
	 */
	public $source;
	/**
	 * @var mixed|null
	 * @description mapped source row
	 */
	public $item;
	/**
	 * @var mixed|null
	 * @description when something wrong occurs, error context could be stored here
	 */
	public $error;
}
