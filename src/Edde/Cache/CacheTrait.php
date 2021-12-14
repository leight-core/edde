<?php
declare(strict_types=1);

namespace Edde\Cache\Service;

use Psr\SimpleCache\CacheInterface;

trait CacheTrait {
	/** @var CacheInterface */
	protected $cache;

	/**
	 * @Inject
	 *
	 * @param CacheInterface $cache
	 */
	public function setCache(CacheInterface $cache): void {
		$this->cache = $cache;
	}
}
