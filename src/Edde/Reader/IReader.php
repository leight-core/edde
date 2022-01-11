<?php
declare(strict_types=1);

namespace Edde\Reader;

use Edde\Progress\IProgress;
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
	public function stream(Generator $generator, IProgress $progress = null): Generator;

	/**
	 * Execute the reader, but do not care about the results.
	 *
	 * @param Generator      $generator
	 * @param IProgress|null $progress
	 */
	public function read(Generator $generator, IProgress $progress = null): void;

	/**
	 * Handle one item of the stream; whatever could happen inside this method.
	 *
	 * @param mixed $item an item being handled
	 *
	 * @return mixed
	 */
	public function handle($item);
}
