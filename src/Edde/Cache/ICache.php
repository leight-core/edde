<?php
declare(strict_types=1);

namespace Edde\Cache;

interface ICache {
	/**
	 * Gets a key from cache; default could be callable - it's result is used if the key does not exists.
	 *
	 * @param string         $key
	 * @param callable|mixed $default
	 *
	 * @return mixed
	 */
	public function get(string $key, $default = null);

	/**
	 * @param string   $key
	 * @param          $value
	 * @param int|null $ttl
	 *
	 * @return mixed
	 */
	public function set(string $key, $value, int $ttl = null);

	/**
	 * Is the given key present?
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has(string $key): bool;

	/**
	 * Delete the given cache key.
	 *
	 * @param string $key
	 */
	public function delete(string $key): void;

	/**
	 * Clear whole cache.
	 */
	public function clear(): void;

	/**
	 * Maintenance function to run a GC with some probability.
	 */
	public function gc(bool $force = false): void;
}
