<?php
declare(strict_types=1);

namespace Edde\Database\Connection;

use Edde\Config\ConfigServiceTrait;

class ConnectionConfig {
	use ConfigServiceTrait;

	public function getConfig(): array {
		return $this->configService->system(static::class, []);
	}
}
