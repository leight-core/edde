<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use Edde\Doctrine\AbstractRepository;
use Edde\Job\Entity\JobLogEntity;

class JobLogRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(JobLogEntity::class);
		$this->orderBy = [
			'stamp' => 'asc',
		];
	}
}
