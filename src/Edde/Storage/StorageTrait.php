<?php
declare(strict_types=1);

namespace Edde\Storage;

use DI\Annotation\Inject;

trait StorageTrait {
	/** @var Storage */
	protected $storage;

	/**
	 * @Inject
	 *
	 * @param Storage $storage
	 */
	public function setStorage(Storage $storage): void {
		$this->storage = $storage;
	}
}
