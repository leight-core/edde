<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use Edde\Database\Repository\AbstractRepository;
use Edde\Job\Schema\JobLog\JobLogSchema;

class JobLogRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(JobLogSchema::class);
		$this->orderBy = [
			'$.stamp' => 'asc',
		];
	}
}
