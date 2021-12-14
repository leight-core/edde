<?php
declare(strict_types=1);

namespace Edde\Tag\Mapper;

trait TagMapperTrait {
	/** @var TagMapper */
	protected $tagMapper;

	/**
	 * @Inject
	 *
	 * @param TagMapper $tagMapper
	 */
	public function setTagMapper(TagMapper $tagMapper): void {
		$this->tagMapper = $tagMapper;
	}
}
