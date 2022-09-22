<?php
declare(strict_types=1);

namespace Edde\Tag\Dto;

use Edde\Dto\AbstractDto;

class TagDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var string
	 */
	public $tag;
	/**
	 * @var string|null
	 */
	public $group;
	/**
	 * @var int
	 */
	public $sort;
}
