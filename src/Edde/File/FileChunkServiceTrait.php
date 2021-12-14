<?php
declare(strict_types=1);

namespace Edde\File;

trait FileChunkServiceTrait {
	/** @var FileChunkService */
	protected $fileChunkService;

	/**
	 * @Inject
	 *
	 * @param FileChunkService $fileChunkService
	 */
	public function setFileChunkService(FileChunkService $fileChunkService): void {
		$this->fileChunkService = $fileChunkService;
	}
}
