<?php
declare(strict_types=1);

namespace Edde\Image\Repository;

trait ImageRepositoryTrait {
	/** @var ImageRepository */
	protected $imageRepository;

	/**
	 * @Inject
	 *
	 * @param ImageRepository $imageRepository
	 */
	public function setImageRepository(ImageRepository $imageRepository): void {
		$this->imageRepository = $imageRepository;
	}
}
