<?php
declare(strict_types=1);

namespace Edde\File;

trait FileGcAsyncServiceTrait {
	/** @var FileGcAsyncService */
	protected $fileGcAsyncService;

	/**
	 * @Inject
	 *
	 * @param FileGcAsyncService $fileGcAsyncService
	 */
	public function setFileGcAsyncService(FileGcAsyncService $fileGcAsyncService): void {
		$this->fileGcAsyncService = $fileGcAsyncService;
	}
}
