<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Import\Importer\ImportProgress;
use Edde\Phpunit\AbstractTestCase;
use Generator;

class ImporterTest extends AbstractTestCase {
	use SomeImporterTrait;

	public function testImporter() {
		$this->someImporter->run($this->importSource(), $progress = new ImportProgress());
	}

	protected function importSource(): Generator {
		yield 1;
	}
}
