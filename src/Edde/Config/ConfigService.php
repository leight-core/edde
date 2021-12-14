<?php
declare(strict_types=1);

namespace Edde\Config;

use Dibi\DriverException;
use Edde\Config\Repository\ConfigRepositoryTrait;
use Edde\Container\Service\ContainerTrait;
use Edde\Log\Service\LoggerTrait;
use Edde\Storage\Service\StorageTrait;
use Throwable;

/**
 * Persistent database configuration support.
 */
class ConfigService {
	use ConfigRepositoryTrait;
	use StorageTrait;
	use ContainerTrait;
	use LoggerTrait;

	protected $configs;

	/**
	 * Create/update all the keys provided in the array.
	 *
	 * @param iterable $config
	 *
	 * @throws DriverException
	 * @throws Throwable
	 */
	public function ensure(iterable $config) {
		$this->storage->transaction(function () use ($config) {
			foreach ($config as $k => $v) {
				$this->configRepository->ensure($k, $v);
			}
		});
	}

	public function update(iterable $config) {
		$this->storage->transaction(function () use ($config) {
			foreach ($config as $k => $v) {
				$this->configRepository->update($k, $v);
			}
		});
	}

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

	public function system(string $key, $default = null) {
		try {
			return $this->container->get($key);
		} catch (Throwable $throwable) {
			return $default;
		}
	}
}
