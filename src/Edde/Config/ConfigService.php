<?php
declare(strict_types=1);

namespace Edde\Config;

use Edde\Config\Repository\ConfigRepositoryTrait;
use Edde\Container\ContainerTrait;
use Edde\Log\LoggerTrait;
use Throwable;

/**
 * Persistent database configuration support.
 */
class ConfigService {
	use ConfigRepositoryTrait;
	use ContainerTrait;
	use LoggerTrait;

	protected $configs;

	public function get(string $key, $default = null) {
		try {
			if (isset($this->configs[$key])) {
				return $this->configs[$key];
			}
			$config = $this->configRepository->findByKey($key);
			return $this->configs[$key] = $config ? $config->value : $default;
		} catch (Throwable $throwable) {
			/**
			 * Cannot use common logger as it's possible that the application does not have any upgrades yet.
			 */
			$this->logger->error($throwable);
			return null;
		}
	}

	public function getBool(string $key, bool $default): bool {
		return filter_var($this->get($key, $default), FILTER_VALIDATE_BOOLEAN);
	}

	public function system(string $key, $default = null) {
		try {
			return $this->container->get($key);
		} catch (Throwable $throwable) {
			return $default;
		}
	}
}
