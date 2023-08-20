<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use Doctrine\ORM\QueryBuilder;
use Edde\Database\Repository\AbstractRepository;
use Edde\Dto\SmartDto;
use Edde\Job\Entity\JobLockEntity;

class JobLockRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(JobLockEntity::class);
		$this->orderBy = [
			'$.stamp' => 'asc',
		];
	}

	protected function applyWhere(SmartDto $filter, SmartDto $query, QueryBuilder $builder): void {
		parent::applyWhere($filter, $query, $builder);
		$filter->knownWithValue('jobId') && $this->matchOf($builder, '$.jobId', $filter->getValue('jobId'));
		$filter->knownWithValue('name') && $this->matchOf($builder, '$.name', $filter->getValue('name'));
		$filter->knownWithValue('active') && $this->matchOf($builder, '$.active', $filter->getValue('active'));
	}
}
