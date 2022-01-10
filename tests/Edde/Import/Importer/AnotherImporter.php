<?php
declare(strict_types=1);

namespace Edde\Import\Importer;

use Edde\Progress\IProgress;

class AnotherImporter extends AbstractImporter {
	public function item($item, IProgress $progress) {
		return 'hovno';
	}
}
