<?php
declare(strict_types=1);

namespace Edde\Translation\Mapper;

trait TranslationMapperTrait {
	/** @var TranslationMapper */
	protected $translationMapper;

	/**
	 * @Inject
	 *
	 * @param TranslationMapper $translationMapper
	 */
	public function setTranslationMapper(TranslationMapper $translationMapper): void {
		$this->translationMapper = $translationMapper;
	}
}
