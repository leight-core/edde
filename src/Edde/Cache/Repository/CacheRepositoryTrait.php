<?php
declare(strict_types=1);

namespace Edde\Cache\Repository;

/**
 * Direct access to low-level repository operating over Cache.
 */
trait CacheRepositoryTrait {
	/** @var CacheRepository */
	protected $cacheRepository;

	/**
	 * @Inject
	 *
	 * @param CacheRepository $cacheRepository
	 */
	public function setCacheRepository(CacheRepository $cacheRepository): void {
		$this->cacheRepository = $cacheRepository;
	}
}
