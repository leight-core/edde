<?php
declare(strict_types=1);

namespace Edde\Import\Importer;

use Edde\Progress\IProgress;

class OverlapImporter extends AbstractImporter {
	protected function item($item, IProgress $progress) {
		return null;
	}
}
