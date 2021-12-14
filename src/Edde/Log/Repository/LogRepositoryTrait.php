<?php
declare(strict_types=1);

namespace Edde\Log\Repository;

trait LogRepositoryTrait {
	/** @var LogRepository */
	protected $logRepository;

	/**
	 * @Inject
	 *
	 * @param LogRepository $logRepository
	 */
	public function setLogRepository(LogRepository $logRepository): void {
		$this->logRepository = $logRepository;
	}
}
