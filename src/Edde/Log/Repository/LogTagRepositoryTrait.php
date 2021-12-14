<?php
declare(strict_types=1);

namespace Edde\Log\Repository;

trait LogTagRepositoryTrait {
	/** @var LogTagRepository */
	protected $logTagRepository;

	/**
	 * @Inject
	 *
	 * @param LogTagRepository $logTagRepository
	 */
	public function setLogTagRepository(LogTagRepository $logTagRepository): void {
		$this->logTagRepository = $logTagRepository;
	}
}
