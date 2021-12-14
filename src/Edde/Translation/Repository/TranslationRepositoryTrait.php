<?php
declare(strict_types=1);

namespace Edde\Translation\Repository;

trait TranslationRepositoryTrait {
	/** @var TranslationRepository */
	protected $translationRepository;

	/**
	 * @Inject
	 *
	 * @param TranslationRepository $translationRepository
	 */
	public function setTranslationRepository(TranslationRepository $translationRepository): void {
		$this->translationRepository = $translationRepository;
	}
}
