<?php
declare(strict_types=1);

namespace Edde\Image;

use Edde\Log\LoggerTrait;
use Imagick;
use Throwable;
use function class_exists;
use function sprintf;

class ImageService implements IImageService {
	use LoggerTrait;

	/**
	 * @inheritdoc
	 */
	public function resize(string $file, int $width, int $height = null, string $copy = null): void {
		$copy = $copy ?? $file;
		$this->logger->debug(sprintf('Resizing image [%s] to [%s]; dimensions [%d x %d].', $file, $copy, $width, $height), ['tags' => [static::class]]);
		try {
			$this->logger->debug('Imagick available ' . (class_exists(Imagick::class) ? 'yes' : 'nope'), ['tags' => [static::class]]);
			$imagick = new Imagick($file);
			$this->logger->debug('Image loaded', ['tags' => [static::class]]);
			$imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 0, isset($height));
			$this->logger->debug('Image resized', ['tags' => [static::class]]);
			$imagick->writeImage($copy);
			$this->logger->debug(sprintf('Image saved to [%s].', $copy), ['tags' => [static::class]]);
		} catch (Throwable $throwable) {
			$this->logger->error($throwable, ['tags' => [static::class]]);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function convert(string $file, string $format, string $copy = null): void {
		$copy = $copy ?? $file;
		$this->logger->debug(sprintf('Converting [%s] to [%s], format [%s].', $file, $copy, $format), ['tags' => [static::class]]);
		try {
			$this->logger->debug('Imagick available ' . (class_exists(Imagick::class) ? 'yes' : 'nope'), ['tags' => [static::class]]);
			$imagick = new Imagick($file);
			$this->logger->debug('Image loaded', ['tags' => [static::class]]);
			$imagick->setFormat($format);
			$this->logger->debug('Format set', ['tags' => [static::class]]);
			$imagick->writeImage($copy);
			$this->logger->debug(sprintf('Image saved to [%s].', $copy), ['tags' => [static::class]]);
		} catch (Throwable $throwable) {
			$this->logger->error($throwable, ['tags' => [static::class]]);
		}
	}
}
