<?php
declare(strict_types=1);

namespace Edde\Cache;

use Edde\Log\Service\LoggerTrait;
use Edde\Math\Service\RandomServiceTrait;
use Psr\SimpleCache\CacheInterface;
use function gzcompress;
use function gzuncompress;
use function serialize;
use function unserialize;

abstract class AbstractCache implements CacheInterface {
	use LoggerTrait;
	use RandomServiceTrait;

	/**
	 * @var array[]
	 */
	protected $cache;

	protected function blob($value): string {
		return gzcompress(serialize($value), 9);
	}

	protected function unblob($value) {
		return unserialize(gzuncompress($value));
	}
}
