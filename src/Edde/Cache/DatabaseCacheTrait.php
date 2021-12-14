<?php
declare(strict_types=1);

namespace Edde\Cache;

/**
 * When one needs explicitly database cache.
 */
trait DatabaseCacheTrait {
	/** @var DatabaseCache */
	protected $databaseCache;

	/**
	 * @Inject
	 *
	 * @param DatabaseCache $databaseCache
	 */
	public function setDatabaseCache(DatabaseCache $databaseCache): void {
		$this->databaseCache = $databaseCache;
	}
}
