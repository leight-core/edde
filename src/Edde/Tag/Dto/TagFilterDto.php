<?php
declare(strict_types=1);

namespace Edde\Tag\Dto;

use Edde\Repository\Dto\AbstractFilterDto;

class TagFilterDto extends AbstractFilterDto {
	/**
	 * @var string[]|null
	 */
	public $groups;
}
