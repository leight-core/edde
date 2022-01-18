<?php
declare(strict_types=1);

namespace Edde\Cache;

use Edde\Log\LoggerTrait;
use Edde\Math\RandomServiceTrait;
use function gzcompress;
use function gzuncompress;
use function is_callable;
use function serialize;
use function unserialize;

/**
 * Just helper class for extended cache; it uses PSR cache under the hood.
 */
abstract class AbstractCache implements ICache {
	use \Edde\Cache\Psr\CacheTrait;
	use LoggerTrait;
	use RandomServiceTrait;

	/**
	 * Local cache; it should be limited to prevent memory leaks here.
	 *
	 * @var array
	 */
	protected $local = [];

	protected function resolveDefault(string $key, $default) {
		return (is_callable($default) ? $default($key) : $default);
	}

	protected function blob($value): string {
		return gzcompress(serialize($value), 9);
	}

	protected function unblob($value) {
		return unserialize(gzuncompress($value));
	}
}
