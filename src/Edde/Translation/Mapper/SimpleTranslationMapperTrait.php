<?php
declare(strict_types=1);

namespace Edde\Translation\Mapper;

trait SimpleTranslationMapperTrait {
	/** @var SimpleTranslationMapper */
	protected $simpleTranslationMapper;

	/**
	 * @Inject
	 *
	 * @param SimpleTranslationMapper $simpleTranslationMapper
	 */
	public function setSimpleTranslationMapper(SimpleTranslationMapper $simpleTranslationMapper): void {
		$this->simpleTranslationMapper = $simpleTranslationMapper;
	}
}
