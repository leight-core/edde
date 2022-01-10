<?php
declare(strict_types=1);

namespace Edde\Reader;

use Generator;

abstract class AbstractReader implements IReader {
	public function read(Generator $generator): void {
		foreach ($this->handle($generator) as $_) ;
	}
}
