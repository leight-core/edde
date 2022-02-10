<?php
declare(strict_types=1);

namespace Edde\Image;

trait ImageJobServiceTrait {
	/** @var ImageJobService */
	protected $imageJobService;

	/**
	 * @Inject
	 *
	 * @param ImageJobService $imageJobService
	 */
	public function setImageJobService(ImageJobService $imageJobService): void {
		$this->imageJobService = $imageJobService;
	}
}
