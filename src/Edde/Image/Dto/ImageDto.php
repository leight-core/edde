<?php
declare(strict_types=1);

namespace Edde\Image\Dto;

use Edde\Dto\AbstractDto;
use Edde\File\Dto\FileDto;

class ImageDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var FileDto
	 */
	public $preview;
	/**
	 * @var string
	 */
	public $previewId;
	/**
	 * @var FileDto
	 */
	public $original;
	/**
	 * @var string
	 */
	public $originalId;
	/**
	 * @var string
	 */
	public $stamp;
	/**
	 * @var string|null
	 */
	public $gallery;
}
