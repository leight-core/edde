<?php
declare(strict_types=1);

namespace Edde\Bulk\Repository;

use Edde\Database\Repository\AbstractRepository;

class BulkRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct();
		$this->orderBy = [
			'$.created' => 'desc',
		];
	}
}
