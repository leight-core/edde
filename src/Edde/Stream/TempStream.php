<?php
declare(strict_types=1);

namespace Edde\Stream;

use Edde\Stream\Exception\StreamException;
use function fopen;
use function fwrite;
use function rewind;
use function tmpfile;

class TempStream extends AbstractStream {
	/**
	 * @param string $resource
	 *
	 * @return TempStream
	 */
	static public function create(string $resource) {
		fwrite($stream = fopen('php://temp', 'r+b'), $resource);
		rewind($stream);
		return new self($stream);
	}

	static public function temp() {
		if (!($resource = tmpfile())) {
			throw new StreamException('Cannot open tmpfile().');
		}
		return new self($resource);
	}
}
