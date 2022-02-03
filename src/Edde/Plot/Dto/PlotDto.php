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
	 * @var DataDto[]
	 */
	public $data = [];
}
