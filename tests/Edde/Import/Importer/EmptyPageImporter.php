<?php
declare(strict_types=1);

namespace Edde\Import\Importer;

use Edde\Reader\AbstractReader;

class EmptyPageImporter extends AbstractReader {
	public function handle($item) {
	}
}
