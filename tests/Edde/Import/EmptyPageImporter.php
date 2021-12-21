<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Progress\IProgress;

class EmptyPageImporter extends AbstractImporter {
	protected function item($item, IProgress $progress) {
		return null;
	}
}
