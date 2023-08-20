<?php
declare(strict_types=1);

namespace Edde\Database\Connection;

use Cake\Database\Connection as CoolConnection;
use Cake\Database\Query;

class Connection {
	use ConnectionConfigTrait;

	/**
	 * @var CoolConnection
	 */
	protected $connection;

	public function getConnection(): CoolConnection {
		return $this->connection ?? $this->connection = new CoolConnection(
			$this->connectionConfig->getConfig()
		);
	}

	public function query(): Query {
		return $this->getConnection()->newQuery();
	}
}
