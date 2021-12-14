<?php
declare(strict_types=1);

namespace Edde\Php;

trait PhpBinaryServiceTrait {
	/** @var IPhpBinaryService */
	protected $phpBinaryService;

	/**
	 * @Inject
	 *
	 * @param IPhpBinaryService $phpBinaryService
	 */
	public function setPhpBinaryService(IPhpBinaryService $phpBinaryService): void {
		$this->phpBinaryService = $phpBinaryService;
	}
}
