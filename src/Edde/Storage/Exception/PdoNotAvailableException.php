<?php
declare(strict_types=1);

namespace Edde\Storage\Exception;

/**
 * When using DBAL and requested an unavailable PDO driver this exception should be thrown.
 */
class PdoNotAvailableException extends StorageException {
}
