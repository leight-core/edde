<?php
declare(strict_types=1);

namespace Edde\Database\Connection;

trait ConnectionConfigTrait {
	/**
	 * @var ConnectionConfig
	 */
	protected $connectionConfig;

	/**
	 * @Inject
	 *
	 * @param ConnectionConfig $connectionConfig
	 *
	 * @return void
	 */
	public function setConnectionConfig(ConnectionConfig $connectionConfig): void {
		$this->connectionConfig = $connectionConfig;
	}
}
