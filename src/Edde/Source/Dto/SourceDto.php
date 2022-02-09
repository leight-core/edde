<?php
declare(strict_types=1);

namespace Edde\Source\Dto;

use Edde\Dto\AbstractDto;

class SourceDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var string
	 */
	public $source;
	/**
	 * @var string
	 */
	public $mapper;
}
