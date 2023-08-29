<?php
declare(strict_types=1);

namespace Edde\Log\Repository;

use Edde\Database\Repository\AbstractRepository;
use Edde\Log\Schema\LogSchema;

class LogRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(LogSchema::class);
		$this->orderBy = [
			'$.microtime' => 'desc',
		];
	}
}
