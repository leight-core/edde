<?php
declare(strict_types=1);

namespace Edde\Storage;

class StorageConfig {
	const CONFIG_STORAGE = 'storage';

	/** @var array */
	protected $config;

	public function __construct(array $config) {
		$this->config = $config;
	}

	public function getConfig(): array {
		return $this->config;
	}
}
