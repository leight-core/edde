<?php
declare(strict_types=1);

namespace Edde\Rpc\Exception;

use Edde\EddeException;

/**
 * Error of this exception is directly propagated to the client, so be careful with
 * messages provided.
 */
class RpcException extends EddeException {
}
