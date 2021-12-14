<?php
declare(strict_types=1);

namespace Edde\File\Mapper;

/**
 * Default file mapper from database row.
 */
trait FileMapperTrait {
	/** @var FileMapper */
	protected $fileMapper;

	/**
	 * @Inject
	 *
	 * @param FileMapper $fileMapper
	 */
	public function setFileMapper(FileMapper $fileMapper): void {
		$this->fileMapper = $fileMapper;
	}
}
