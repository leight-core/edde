<?php
declare(strict_types=1);

namespace Edde\Database\Connection;

trait ConnectionTrait {
	/**
	 * @var Connection
	 */
	protected $connection;

	/**
	 * @Inject
	 *
	 * @param Connection $connection
	 *
	 * @return void
	 */
	public function setConnection(Connection $connection): void {
		$this->connection = $connection;
	}
}
