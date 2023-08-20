<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use Cake\Database\Query;
use Edde\Database\Repository\AbstractRepository;
use Edde\Dto\SmartDto;
use Edde\Job\Schema\JobLock\JobLockSchema;

class JobLockRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(JobLockSchema::class);
		$this->orderBy = [
			'$.stamp' => 'asc',
		];
	}

	protected function applyWhere(SmartDto $filter, SmartDto $query, Query $builder): void {
		parent::applyWhere($filter, $query, $builder);
		$filter->knownWithValue('jobId') && $this->matchOf($builder, '$.jobId', $filter->getValue('jobId'));
		$filter->knownWithValue('name') && $this->matchOf($builder, '$.name', $filter->getValue('name'));
		$filter->knownWithValue('active') && $this->matchOf($builder, '$.active', $filter->getValue('active'));
	}
}
