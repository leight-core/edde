<?php
declare(strict_types=1);

namespace Edde\Image;

use Imagick;
use ImagickException;

class ImageService implements IImageService {
	/**
	 * @param string      $file
	 * @param int         $width
	 * @param int|null    $height
	 * @param string|null $copy
	 *
	 * @throws ImagickException
	 */
	public function resize(string $file, int $width, int $height = null, string $copy = null): void {
		$copy = $copy ?? $file;
		$imagick = new Imagick($file);
		$imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 0, isset($height));
		$imagick->writeImage($copy);
	}
}
