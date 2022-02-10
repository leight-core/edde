<?php
declare(strict_types=1);

namespace Edde\Image;

use Imagick;

class ImageService implements IImageService {
	/**
	 * @inheritdoc
	 */
	public function resize(string $file, int $width, int $height = null, string $copy = null): void {
		$copy = $copy ?? $file;
		$imagick = new Imagick($file);
		$imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 0, isset($height));
		$imagick->writeImage($copy);
	}

	/**
	 * @inheritdoc
	 */
	public function convert(string $file, string $format, string $copy = null): void {
		$copy = $copy ?? $file;
		$imagick = new Imagick($file);
		$imagick->setFormat($format);
		$imagick->writeImage($copy);
	}
}
