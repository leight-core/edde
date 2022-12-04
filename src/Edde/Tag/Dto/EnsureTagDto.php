<?php
declare(strict_types=1);

namespace Edde\Tag\Dto;

class EnsureTagDto {
	/**
	 * @var string
	 */
	public $tag;
	/**
	 * @var string
	 */
	public $group;
	/**
	 * @var int|void
	 */
	public $sort;
}
