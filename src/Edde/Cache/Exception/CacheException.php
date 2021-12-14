<?php
declare(strict_types=1);

namespace Edde\Cache\Exception;

use Edde\EddeException;
use Psr\SimpleCache\CacheException as CacheExceptionInterface;

class CacheException extends EddeException implements CacheExceptionInterface {
}
