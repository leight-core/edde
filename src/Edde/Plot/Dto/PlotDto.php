<?php
declare(strict_types=1);

namespace Edde\Plot\Dto;

use Edde\Dto\AbstractDto;

class PlotDto extends AbstractDto {
	/**
	 * @var bool
	 */
	public $isStack = false;
	/**
	 * @var bool
	 */
	public $isGroup = false;
	/**
	 * @var string
	 */
	public $x = 'column';
	/**
	 * @var string
	 */
	public $y = 'value';
	/**
	 * @var string
	 */
	public $group = 'group';
	/**
	 * @var int
	 */
	public $count;
	/**
	 * @var DataDto[]
	 */
	public $data = [];
}
