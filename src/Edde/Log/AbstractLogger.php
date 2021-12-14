<?php
declare(strict_types=1);

namespace Edde\Log;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait as PsrLoggerTrait;

abstract class AbstractLogger implements LoggerInterface {
	use PsrLoggerTrait;
	use TraceServiceTrait;
}
