<?php
declare(strict_types=1);

namespace Edde\Stream;

use Edde\Stream\Exception\StreamException;
use function fopen;
use function is_readable;
use function sprintf;

class FileStream extends AbstractStream {
	/**
	 * Open file stream for appending
	 *
	 * @param string $file
	 *
	 * @return FileStream
	 */
	static public function openAppend(string $file) {
		if (!($resource = fopen($file, 'ab'))) {
			throw new StreamException(sprintf('Cannot open [%s] for appending (ab+).', $file));
		}
		return new self($resource);
	}

	/**
	 * Open file stream for writing (wb+).
	 *
	 * @param string $file
	 *
	 * @return FileStream
	 */
	static public function openWrite(string $file) {
		if (!($resource = fopen($file, 'wb'))) {
			throw new StreamException(sprintf('Cannot open [%s] for writing (wb+).', $file));
		}
		return new self($resource);
	}

	/**
	 * Open file stream for reading (r+).
	 *
	 * @param string $file
	 *
	 * @return FileStream
	 *
	 * @throws StreamException
	 */
	static public function openRead(string $file) {
		if (!is_readable($file)) {
			throw new StreamException(sprintf('File [%s] is not readable!', $file));
		}
		if (!($resource = fopen($file, 'r'))) {
			throw new StreamException(sprintf('Cannot open [%s] for reading (r).', $file));
		}
		return new self($resource);
	}
}
