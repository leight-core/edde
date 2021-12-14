<?php
declare(strict_types=1);

namespace Edde\File;

/**
 * File service is used to "somehow" store files.
 */
trait FileServiceTrait {
	/** @var IFileService */
	protected $fileService;

	/**
	 * @Inject
	 *
	 * @param IFileService $fileService
	 */
	public function setFileService(IFileService $fileService): void {
		$this->fileService = $fileService;
	}
}
