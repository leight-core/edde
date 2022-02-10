<?php
declare(strict_types=1);

namespace Edde\Image;

trait ImageServiceTrait {
	/** @var IImageService */
	protected $imageService;

	/**
	 * @Inject
	 *
	 * @param IImageService $imageService
	 */
	public function setImageService(IImageService $imageService): void {
		$this->imageService = $imageService;
	}
}
