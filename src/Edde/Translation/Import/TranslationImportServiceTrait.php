<?php
declare(strict_types=1);

namespace Edde\Translation\Import;

trait TranslationImportServiceTrait {
	/** @var TranslationImportService */
	protected $translationImportService;

	/**
	 * @Inject
	 *
	 * @param TranslationImportService $translationImportService
	 */
	public function setTranslationImportService(TranslationImportService $translationImportService): void {
		$this->translationImportService = $translationImportService;
	}
}
