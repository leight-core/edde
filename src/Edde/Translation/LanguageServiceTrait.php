<?php
declare(strict_types=1);

namespace Edde\Translation;

/**
 * Language service is used server-side to determine language of the current user (also on the
 * server side).
 *
 * It's source of truth for language detection from user's settings or some other place from a default
 * value could came.
 */
trait LanguageServiceTrait {
	/** @var LanguageService */
	protected $languageService;

	/**
	 * @Inject
	 *
	 * @param LanguageService $languageService
	 */
	public function setLanguageService(LanguageService $languageService): void {
		$this->languageService = $languageService;
	}
}
