<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Import\Importer\SomeImporterTrait;
use Edde\Phpunit\AbstractTestCase;
use Generator;

class ImporterTest extends AbstractTestCase {
	use SomeImporterTrait;

	public function testImporter() {
	}

	protected function importSource(): Generator {
		yield 1;
	}
}
