<?php
declare(strict_types=1);

namespace Edde\Import\Template\Repository;

trait ImportTemplateRepositoryTrait {
	/** @var ImportTemplateRepository */
	protected $importTemplateRepository;

	/**
	 * @Inject
	 *
	 * @param ImportTemplateRepository $importTemplateRepository
	 */
	public function setImportTemplateRepository(ImportTemplateRepository $importTemplateRepository): void {
		$this->importTemplateRepository = $importTemplateRepository;
	}
}
