<?php
declare(strict_types=1);

namespace Edde\Import\Importer;

use Edde\Import\Importer\Dto\SomeImportDto;
use Edde\Reader\AbstractReader;

class SomeImporter extends AbstractReader {
	/**
	 * @param SomeImportDto $item
	 */
	public function handle($item) {

	}
}
