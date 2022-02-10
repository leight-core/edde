<?php
declare(strict_types=1);

namespace Edde\Image\Dto;

use Edde\Repository\Dto\AbstractFilterDto;

class ImageFilterDto extends AbstractFilterDto {
	/**
	 * @var string|null|void
	 */
	public $gallery;
	/**
	 * @var string|null|void
	 */
	public $userId;
}
