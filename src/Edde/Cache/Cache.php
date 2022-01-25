<?php
declare(strict_types=1);

namespace Edde\Cache;

use Edde\Cache\Exception\InvalidEntryException;
use Throwable;
use function array_slice;
use function count;
use function sha1;
use function sprintf;

class Cache extends AbstractCache {
	public function get(string $key, $default = null) {
		try {
			$this->gc();
			if (isset($this->local[$keyHash = $this->key($key)])) {
				return $this->local[$keyHash];
			}
			if (!($item = $this->cache->get($keyHash))) {
				return $this->resolveDefault($key, $default);
			}
			if (($hash = sha1($item[0])) !== $item[1]) {
				throw new InvalidEntryException(sprintf('Cached item [%s] is invalid: computed hash [%s] does not match item hash [%s].', $key, $hash, $item->hash));
			}
			return $this->local[$keyHash] = $this->unblob($item[0]);
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
			return $this->resolveDefault($key, $default);
		}
	}

	public function set(string $key, $value, int $ttl = null) {
		try {
			$this->gc();
			if (count($this->local) > 512) {
				$this->local = array_slice($this->local, 0, 128, true);
			}
			$this->local[$key = $this->key($key)] = $value;
			$this->cache->set($key, [
				$blob = $this->blob($value),
				sha1($blob),
			], $ttl);
			return $value;
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
			return $value;
		}
	}

	public function delete(string $key): void {
		try {
			$this->gc();
			$this->cache->delete($this->key($key));
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
		}
	}

	public function clear(): void {
		try {
			$this->cache->clear();
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
		}
	}

	public function has(string $key): bool {
		$this->gc();
		return $this->cache->has($this->key($key));
	}

	/**
	 * Run GC with some probability.
	 */
	public function gc(bool $force = false): void {
		/**
		 * Cache cleanup with probability.
		 */
//		if ($force || $this->randomService->isHit(1 / 10000)) {
//			$this->local = [];
//			$this->cache->clear();
//		}
	}
}
