<?php
declare(strict_types=1);

namespace Edde\Translation;

/**
 * This service is used just for server-side translations (initial usage for localised Excel imports).
 *
 * DO NOt use it for translating client-side strings.
 */
trait TranslationServiceTrait {
	/** @var TranslationService */
	protected $translationService;

	/**
	 * @Inject
	 *
	 * @param TranslationService $translationService
	 */
	public function setTranslationService(TranslationService $translationService): void {
		$this->translationService = $translationService;
	}
}
