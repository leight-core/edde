<?php
declare(strict_types=1);

namespace Edde\Image\Dto;

use Edde\Dto\AbstractDto;

class CreateDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $previewId;
	/**
	 * @var string
	 */
	public $originalId;
	/**
	 * @var string|null
	 */
	public $gallery;
	/**
	 * @var string
	 */
	public $userId;
}
