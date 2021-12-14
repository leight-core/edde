<?php
declare(strict_types=1);

namespace Edde\Dto\Service;

use Edde\Dto\IDtoService;

/**
 * A service used to map an array/object/whatever into a typed class.
 *
 * This trait uses a default service (configured in the DI container) against an interface.
 */
trait DtoServiceTrait {
	/** @var IDtoService */
	protected $dtoService;

	/**
	 * @Inject
	 *
	 * @param IDtoService $dtoService
	 */
	public function setDtoService(IDtoService $dtoService): void {
		$this->dtoService = $dtoService;
	}
}
