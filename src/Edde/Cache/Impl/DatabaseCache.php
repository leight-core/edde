<?php
declare(strict_types=1);

namespace Edde\Cache\Impl;

use Edde\Cache\Exception\CacheException;
use Edde\Cache\Psr\AbstractCache;
use Edde\Cache\Repository\CacheRepositoryTrait;
use Throwable;

class DatabaseCache extends AbstractCache {
	use CacheRepositoryTrait;

	public function get($key, $default = null) {
		try {
			if (!($key = $this->cacheRepository->fetchKey($key))) {
				return null;
			}
			return [
				$key->value,
				$key->hash,
			];
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
			return null;
		}
	}

	public function set($key, $value, $ttl = null) {
		try {
			$this->cacheRepository->ensure($key, $value, $ttl);
			return true;
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
			return false;
		}
	}

	public function has($key) {
		return $this->cacheRepository->has($key);
	}

	public function delete($key) {
		try {
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

	public function deleteMultiple($keys) {
		try {
			$this->cacheRepository->table()->delete()->where(['key' => $keys])->execute();
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
}
