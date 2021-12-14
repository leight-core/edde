<?php
declare(strict_types=1);

namespace Edde\Translation;

use Dibi\Exception;
use Edde\Translation\Repository\TranslationRepositoryTrait;
use Marsh\User\Exception\UserNotSelectedException;

class TranslationService {
	use TranslationRepositoryTrait;
	use LanguageServiceTrait;

	/**
	 * Do a translation; language is based on the LanguageService. For explicit translation use "translate" method.
	 *
	 * @param string      $label
	 * @param string|null $default
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @throws UserNotSelectedException
	 */
	public function translation(string $label, string $default = null): string {
		return $this->translate($label, $this->languageService->forCurrentUser(), $default);
	}

	/**
	 * Do a translation; language must be provided or explicit NULL for cases where a translation is not needed.
	 *
	 * @param string      $label
	 * @param string|null $language
	 * @param string|null $default
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function translate(string $label, ?string $language, string $default = null): string {
		if (!$language) {
			return $default ?? $label;
		}
		return $this->translationRepository->fetchByKey($language, $label)->translation ?? ($default ?? $label);
	}
}
