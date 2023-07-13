<?php
declare(strict_types=1);

namespace Edde\Rpc\Schema;

interface RpcBulkRequestSchema {
	function bulk($load = true, $array = true): RpcRequestSchema;
}
