<?php
declare(strict_types=1);

namespace Edde\Reader;

use Generator;

interface IReader {
	/**
	 * This method is kind of "pipe" it has to be iterated as every input item could have it's output.
	 *
	 * That means an input generator is processed as a stream, so it can handle arbitrary large data, so the output
	 * must be handled the same.
	 *
	 * @param Generator $generator
	 *
	 * @return Generator
	 */
	public function stream(Generator $generator): Generator;

	/**
	 * Execute the reader, but do not care about the results.
	 *
	 * @param Generator $generator
	 */
	public function read(Generator $generator): void;

	/**
	 * Handle one item of the stream; whatever could happen inside this method.
	 *
	 * @param mixed $item
	 *
	 * @return mixed
	 */
	public function handle($item);
}
