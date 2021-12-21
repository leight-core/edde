<?php
declare(strict_types=1);

namespace Edde\Import;

trait SomeImporterTrait {
	/** @var SomeImporter */
	protected $someImporter;

	/**
	 * @Inject
	 *
	 * @param SomeImporter $someImporter
	 */
	public function setSomeImporter(SomeImporter $someImporter): void {
		$this->someImporter = $someImporter;
	}
}
