<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Progress\IProgress;

class SomeImporter extends AbstractImporter {
	public function item($item, IProgress $progress) {
		return 'foo';
	}
}
