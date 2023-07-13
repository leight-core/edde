<?php
declare(strict_types=1);

namespace Edde\Rpc\Exception;

use Exception;

/**
 * Error of this exception is directly propagated to the client, so be careful with
 * messages provided.
 */
class RpcException extends Exception {
}
