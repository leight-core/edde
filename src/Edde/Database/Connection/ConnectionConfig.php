<?php
declare(strict_types=1);

namespace Edde\Database\Connection;

use Edde\Config\ConfigServiceTrait;

class ConnectionConfig {
	use ConfigServiceTrait;

	const CONFIG = ConnectionConfig::class . '.config';

	public function getConfig(): array {
		return $this->configService->system(static::CONFIG, []);
	}
}
