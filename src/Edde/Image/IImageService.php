<?php
declare(strict_types=1);

namespace Edde\Image;

interface IImageService {
	/**
	 * Resize the given file; when copy not specified, file will be replaced.
	 *
	 * @param string      $file
	 * @param int         $width
	 * @param int|null    $height
	 * @param string|null $copy
	 */
	public function resize(string $file, int $width, int $height = null, string $copy = null): void;

	/**
	 * TRy to convert an image; is copy is not specified, source file will be replaced.
	 *
	 * @param string      $file
	 * @param string      $format
	 * @param string|null $copy
	 *
	 * @return void
	 */
	public function convert(string $file, string $format, string $copy = null): void;
}
