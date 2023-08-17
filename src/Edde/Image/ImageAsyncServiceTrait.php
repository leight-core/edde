<?php
declare(strict_types=1);

namespace Edde\Image;

trait ImageAsyncServiceTrait {
	/** @var ImageAsyncService */
	protected $imageAsyncService;

	/**
	 * @Inject
	 *
	 * @param ImageAsyncService $imageAsyncService
	 */
	public function setImageAsyncService(ImageAsyncService $imageAsyncService): void {
		$this->imageAsyncService = $imageAsyncService;
	}
}
