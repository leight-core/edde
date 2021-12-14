<?php
declare(strict_types=1);

namespace Edde\Uuid;

trait UuidServiceTrait {
	/** @var UuidService */
	protected $uuidService;

	/**
	 * @Inject
	 *
	 * @param UuidService $uuidService
	 */
	public function setUuidService(UuidService $uuidService): void {
		$this->uuidService = $uuidService;
	}
}
