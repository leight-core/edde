<?php
declare(strict_types=1);

namespace Edde\File\Repository;

/**
 * Core (low-level) file repository with free access to all files.
 */
trait FileRepositoryTrait {
	/** @var FileRepository */
	protected $fileRepository;

	/**
	 * @Inject
	 *
	 * @param FileRepository $fileRepository
	 */
	public function setFileRepository(FileRepository $fileRepository): void {
		$this->fileRepository = $fileRepository;
	}
}
