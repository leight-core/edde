<?php
declare(strict_types=1);

namespace Edde\Cache\Impl;

use Edde\Cache\Exception\CacheException;
use Psr\SimpleCache\CacheInterface;
use Throwable;
use function is_iterable;
use function iterator_to_array;

/**
 * @Injectable(lazy=true)
 */
class DatabaseCache implements CacheInterface {
	public function get($key, $default = null) {
		try {
			return null;
		} catch (Throwable $throwable) {
			return null;
		}
	}

	public function set($key, $value, $ttl = null) {
		try {
			return true;
		} catch (Throwable $throwable) {
			return false;
		}
	}

	public function has($key) {
		return false;
	}

	public function delete($key) {
		try {
			return true;
		} catch (Throwable $throwable) {
			return false;
		}
	}

	public function clear() {
		try {
			return true;
		} catch (Throwable $throwable) {
			return false;
		}
	}

	public function deleteMultiple($keys) {
		try {
			$keys = is_iterable($keys) ? iterator_to_array($keys) : $keys;
			return true;
		} catch (Throwable $throwable) {
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
