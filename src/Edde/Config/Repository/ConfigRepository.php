<?php
declare(strict_types=1);

namespace Edde\Config\Repository;

use Edde\Config\Schema\ConfigSchema;
use Edde\Database\Repository\AbstractRepository;

class ConfigRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(ConfigSchema::class);
		$this->orderBy = [
			'$.key' => 'asc',
		];
	}
}
