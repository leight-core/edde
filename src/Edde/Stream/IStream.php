<?php
declare(strict_types=1);

namespace Edde\Stream;

use Edde\Stream\Exception\StreamException;

interface IStream {
	/**
	 * @return resource
	 */
	public function stream();

	/**
	 * Get stream contents
	 *
	 * @return string
	 */
	public function get();

	public function length(): int;

	/**
	 * @param string $source
	 */
	public function put(string $source): IStream;

	/**
	 * Write to the given stream (as is without any position modification).
	 *
	 * @param IStream $stream
	 */
	public function toStream(IStream $stream): IStream;

	/**
	 * Use the stream and close it after usage (also when an exception occurs).
	 *
	 * @param callable $callback IStream ($this) is the only parameter
	 */
	public function use(callable $callback);

	/**
	 * Same as use() and toStream() methods; copy stream and close the source.
	 *
	 * @param IStream $stream
	 */
	public function useToStream(IStream $stream): IStream;

	/**
	 * Write contents of the file into current stream
	 *
	 * @param string $file
	 *
	 * @throws StreamException
	 */
	public function write(string $file): IStream;

	public function rewind(): IStream;
}
