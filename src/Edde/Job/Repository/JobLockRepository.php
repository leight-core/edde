<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use Edde\Repository\AbstractRepository;
use Edde\Repository\IRepository;

class JobLockRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(['stamp' => IRepository::ORDER_ASC]);
	}
}
