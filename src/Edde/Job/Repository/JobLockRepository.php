<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use Edde\Doctrine\AbstractRepository;
use Edde\Job\Entity\JobLockEntity;

class JobLockRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(JobLockEntity::class);
		$this->orderBy = [
			'stamp' => 'asc',
		];
	}
}
