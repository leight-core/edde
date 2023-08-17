<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use Doctrine\ORM\QueryBuilder;
use Edde\Doctrine\AbstractRepository;
use Edde\Dto\SmartDto;
use Edde\Job\Entity\JobLockEntity;

class JobLockRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(JobLockEntity::class);
		$this->orderBy = [
			'stamp' => 'asc',
		];
	}

	protected function applyWhere(string $alias, SmartDto $filter, SmartDto $query, QueryBuilder $queryBuilder): void {
		parent::applyWhere($alias, $filter, $query, $queryBuilder);
		$filter->knownWithValue('jobId') && $this->matchOf($queryBuilder, $alias, '$.jobId', $filter->getValue('jobId'));
		$filter->knownWithValue('name') && $this->matchOf($queryBuilder, $alias, '$.name', $filter->getValue('name'));
		$filter->knownWithValue('active') && $this->matchOf($queryBuilder, $alias, '$.active', $filter->getValue('active'));
	}
}
