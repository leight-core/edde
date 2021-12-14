<?php
declare(strict_types=1);

namespace Edde\Translation\Mapper;

trait TranslatingMapperTrait {
	/** @var TranslatingMapper */
	protected $translatingMapper;

	/**
	 * @Inject
	 *
	 * @param TranslatingMapper $translatingMapper
	 */
	public function setTranslatingMapper(TranslatingMapper $translatingMapper): void {
		$this->translatingMapper = $translatingMapper;
	}
}
