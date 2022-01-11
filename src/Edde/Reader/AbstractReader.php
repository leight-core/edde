<?php
declare(strict_types=1);

namespace Edde\Reader;

use Generator;

abstract class AbstractReader implements IReader {
	public function stream(Generator $generator): Generator {
		foreach ($generator as $item) {
			yield $this->handle($item);
		}
	}

	public function read(Generator $generator): void {
		foreach ($this->stream($generator) as $_) ;
	}
}
