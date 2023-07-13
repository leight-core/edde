<?php
declare(strict_types=1);

namespace Edde\Rpc\Schema;

abstract class RpcRequestSchema {
	abstract public function service(): string;

	abstract public function data(): array;
}
