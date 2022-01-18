<?php
declare(strict_types=1);

namespace Edde\Cache\Psr;

use Edde\Log\LoggerTrait;
use Psr\SimpleCache\CacheInterface;

abstract class AbstractCache implements CacheInterface {
	use LoggerTrait;
}
