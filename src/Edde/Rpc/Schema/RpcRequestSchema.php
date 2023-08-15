<?php
declare(strict_types=1);

namespace Edde\Rpc\Schema;

interface RpcRequestSchema {
	function service(): string;

	function data(): ?array;
}
