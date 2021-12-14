<?php
declare(strict_types=1);

namespace Edde\Stream;

use Edde\Stream\Exception\StreamException;
use Nyholm\Psr7\Stream;
use Psr\Http\Message\StreamInterface;
use function fclose;
use function fopen;
use function fread;
use function fwrite;
use function is_readable;
use function rewind;
use function sprintf;
use function stream_copy_to_stream;
use function stream_get_contents;
use function strlen;

abstract class AbstractStream implements IStream {
	/** @var resource */
	protected $resource;

	public function __construct($resource) {
		$this->resource = $resource;
	}

	/**
	 * @inheritdoc
	 */
	public function stream() {
		return $this->resource;
	}

	public function get() {
		$this->rewind();
		return stream_get_contents($this->resource);
	}

	public function length(): int {
		$this->rewind();
		$size = 0;
		while ($chunk = fread($this->resource, 2048)) {
			$size += strlen($chunk);
		}
		return $size;
	}

	public function put(string $source): IStream {
		fwrite($this->resource, $source);
		$this->rewind();
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function toStream(IStream $stream): IStream {
		@stream_copy_to_stream($this->resource, $stream->stream());
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function use(callable $callback) {
		try {
			return $callback($this);
		} finally {
			fclose($this->resource);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function useToStream(IStream $stream): IStream {
		$this->use(function (IStream $self) use ($stream) {
			$stream->toStream($self);
		});
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function write(string $file): IStream {
		if (!is_readable($file)) {
			throw new StreamException(sprintf('File [%s] is not readable!', $file));
		}
		if (!($resource = fopen($file, 'r'))) {
			throw new StreamException(sprintf('Cannot open source file [%s] for reading (r).', $file));
		}
		stream_copy_to_stream($resource, $this->resource);
		fclose($resource);
		return $this;
	}

	public function rewind(): IStream {
		rewind($this->resource);
		return $this;
	}

	public function asStream(): StreamInterface {
		return Stream::create($this->resource);
	}
}
