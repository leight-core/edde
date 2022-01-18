<?php
declare(strict_types=1);

namespace Edde\Cache;

/**
 * This is wrapper around PSR cache interface with some extended functions.
 */
trait CacheTrait {
	/** @var ICache */
	protected $cache;

	/**
	 * @Inject
	 *
	 * @param ICache $cache
	 */
	public function setCache(ICache $cache): void {
		$this->cache = $cache;
	}
}
