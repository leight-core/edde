<?php
declare(strict_types=1);

namespace Edde\Cache;

use Edde\Cache\Exception\CacheException;
use Edde\Cache\Exception\InvalidEntryException;
use Edde\Cache\Repository\CacheRepositoryTrait;
use Throwable;

class DatabaseCache extends AbstractCache {
	use CacheRepositoryTrait;

	public function get($key, $default = null) {
		try {
			$this->gc();
			if (isset($this->cache[$key])) {
				return $this->cache[$key];
			}
			/**
			 * Here is a little trick to prevent race condition:
			 *
			 * Fetch key is done before "has" because if an item will be removed between these two calls, fetchKey will return NULL. This solution will ensure that call site will
			 * properly get stored NULL values or computed defaults.
			 */
			$item = $this->cacheRepository->fetchKey($key);
			if ($item && ($hash = sha1($item->value)) !== $item->hash) {
				throw new InvalidEntryException(sprintf('Cached item [%s] is invalid: computed hash [%s] does not match item hash [%s].', $key, $hash, $item->hash));
			}
			if ($item && $this->has($key)) {
				return $this->cache[$key] = $this->unblob($item->value);
			}
			return (is_callable($default) ? $default($key) : $default);
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
			return (is_callable($default) ? $default($key) : $default);
		}
	}

	public function set($key, $value, $ttl = null) {
		try {
			$this->gc();
			$this->cache[$key] = $value;
			$this->cacheRepository->ensure($key, $this->blob($value), $ttl);
			return true;
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
			return false;
		}
	}

	public function delete($key) {
		try {
			$this->gc();
			$this->cacheRepository->table()->delete()->where(['key' => $key])->execute();
			return true;
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
			return false;
		}
	}

	public function clear() {
		try {
			$this->cacheRepository->table()->delete()->execute();
			return true;
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
			return false;
		}
	}

	public function getMultiple($keys, $default = null) {
		throw new CacheException("Not implemented yet.");
	}

	public function setMultiple($values, $ttl = null) {
		throw new CacheException("Not implemented yet.");
	}

	public function deleteMultiple($keys) {
		try {
			$this->cacheRepository->table()->delete()->where(['key' => $keys])->execute();
			return true;
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
			return false;
		}
	}

	public function has($key) {
		$this->gc();
		return $this->cacheRepository->has($key);
	}

	/**
	 * Run GC with some probability.
	 */
	public function gc() {
		/**
		 * Cache cleanup with probability.
		 */
		if ($this->randomService->isHit(1 / 250)) {
			$this->cache = [];
			$this->cacheRepository->cleanup();
		}
	}
}
