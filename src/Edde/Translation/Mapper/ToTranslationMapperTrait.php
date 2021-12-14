<?php
declare(strict_types=1);

namespace Edde\Translation\Mapper;

trait ToTranslationMapperTrait {
	/** @var ToTranslationMapper */
	protected $toTranslationMapper;

	/**
	 * @Inject
	 *
	 * @param ToTranslationMapper $toTranslationMapper
	 */
	public function setToTranslationMapper(ToTranslationMapper $toTranslationMapper): void {
		$this->toTranslationMapper = $toTranslationMapper;
	}
}
