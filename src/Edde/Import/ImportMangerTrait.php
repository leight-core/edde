<?php
declare(strict_types=1);

namespace Edde\Import;

trait ImportMangerTrait {
	/** @var IImportManager */
	protected $importManager;

	/**
	 * @Inject
	 *
	 * @param IImportManager $importManager
	 */
	public function setImportManager(IImportManager $importManager): void {
		$this->importManager = $importManager;
	}
}
