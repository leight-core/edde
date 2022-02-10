<?php
declare(strict_types=1);

namespace Edde\File;

trait FileGcServiceTrait {
	/** @var FileGcService */
	protected $fileGcService;

	/**
	 * @Inject
	 *
	 * @param FileGcService $fileGcService
	 */
	public function setFileGcService(FileGcService $fileGcService): void {
		$this->fileGcService = $fileGcService;
	}
}
