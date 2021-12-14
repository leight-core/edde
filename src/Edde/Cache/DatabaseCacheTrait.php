<?php
declare(strict_types=1);

namespace Edde\Cache\Service;

use Edde\Cache\DatabaseCache;

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
