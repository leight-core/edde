<?php
declare(strict_types=1);

namespace Edde\Import;

trait ImportMangerTrait {
	/** @var ImportManager */
	protected $importManager;

	/**
	 * @Inject
	 *
	 * @param ImportManager $importManager
	 */
	public function setImportManager(ImportManager $importManager): void {
		$this->importManager = $importManager;
	}
}
