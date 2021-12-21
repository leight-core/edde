<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Progress\IProgress;
use Generator;

abstract class AbstractImporter implements IImporter {
	public function import(iterable $source, IProgress $progress): Generator {
		foreach ($source as $item) {
			yield $this->item($item, $progress);
		}
	}

	public function run(iterable $source, IProgress $progress): void {
		foreach ($this->import($source, $progress) as $_) ;
	}

	abstract protected function item($item, IProgress $progress);
}
