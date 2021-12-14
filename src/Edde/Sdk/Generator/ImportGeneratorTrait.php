<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

trait ImportGeneratorTrait {
	/** @var ImportGenerator */
	protected $importGenerator;

	/**
	 * @Inject
	 *
	 * @param ImportGenerator $importGenerator
	 */
	public function setImportGenerator(ImportGenerator $importGenerator): void {
		$this->importGenerator = $importGenerator;
	}
}
