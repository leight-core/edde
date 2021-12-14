<?php
declare(strict_types=1);

namespace Edde\Cache\Repository;

use ClanCats\Hydrahon\Query\Sql\SelectBase;
use Dibi\Exception;
use Edde\Repository\AbstractRepository;
use Edde\Repository\Exception\DuplicateEntryException;
use Throwable;
use function date;

class CacheRepository extends AbstractRepository {
	public function ensure(string $key, string $value, int $ttl = null) {
		try {
			return $this->insert([
				'key'   => $key,
				'value' => $value,
				'hash'  => sha1($value),
				'ttl'   => $ttl ? date("Y-m-d H:i:s", strtotime("+$ttl sec")) : $ttl,
			]);
		} catch (DuplicateEntryException $exception) {
			return $this->change([
				'id'    => $this->select('id')->where('key', $key)->execute()->fetchSingle(),
				'value' => $value,
				'ttl'   => $ttl ? date("Y-m-d H:i:s", strtotime("+$ttl sec")) : $ttl,
			]);
		} catch (Throwable $throwable) {
			/**
			 * Swallow - cache is not important enough to kill the whole app; also logger may not be available.
			 */
			return null;
		}
	}

	/**
	 * @param string $key
	 *
	 * @return array
	 *
	 * @throws Exception
	 */
	public function fetchKey(string $key) {
		try {
			return $this->select()->where(function (SelectBase $selectBase) {
				$selectBase->whereNull('ttl')->orWhere('ttl', '>=', date('Y-m-d H:i:s'));
			})->where('key', $key)->execute()->fetch();
		} catch (Throwable $exception) {
			/**
			 * Swallow as the cache itself is not important enough to kill the app; also logger may not be available.
			 *
			 * To detect some shit inside, the app will run slowly (so, enable profiler and you'll see).
			 */
			return null;
		}
	}

	public function has(string $key) {
		return $this->fetchKey($key) !== null;
	}

	/**
	 * Cleanup dead items (passed TTL)
	 */
	public function cleanup() {
		try {
			$this->table()->delete()->whereNotNull('ttl')->where('ttl', '<', date('Y-m-d H:i:s'))->execute();
		} catch (Throwable $throwable) {
			/**
			 * Swallow - cache stuff should not kill the whole app (this could be called randomly be gc() method).
			 */
		}
	}
}
