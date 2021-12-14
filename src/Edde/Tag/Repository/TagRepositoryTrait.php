<?php
declare(strict_types=1);

namespace Edde\Tag\Repository;

trait TagRepositoryTrait {
	/** @var TagRepository */
	protected $tagRepository;

	/**
	 * @Inject
	 *
	 * @param TagRepository $tagRepository
	 */
	public function setTagRepository(TagRepository $tagRepository): void {
		$this->tagRepository = $tagRepository;
	}
}
