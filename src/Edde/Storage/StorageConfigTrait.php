<?php
declare(strict_types=1);

namespace Edde\Storage;

use DI\Annotation\Inject;

trait StorageConfigTrait {
	/** @var StorageConfig */
	protected $storageConfig;

	/**
	 * @Inject
	 *
	 * @param StorageConfig $storageConfig
	 */
	public function setStorageConfig(StorageConfig $storageConfig): void {
		$this->storageConfig = $storageConfig;
	}
}
