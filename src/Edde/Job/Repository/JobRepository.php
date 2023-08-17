<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use Doctrine\ORM\QueryBuilder;
use Edde\Doctrine\AbstractRepository;
use Edde\Dto\SmartDto;
use Edde\Job\Entity\JobEntity;
use Edde\Job\Schema\JobStatus;

class JobRepository extends AbstractRepository {
	use JobLogRepositoryTrait;

	public function __construct() {
		parent::__construct(JobEntity::class);
		$this->orderBy = [
			'$.created' => 'desc',
		];
		$this->fulltextOf = [
			'$.id',
			'$.service',
			'$.params',
		];
		$this->matchOf = [
			'$.user_id',
			'$.service',
		];
	}

	protected function applyWhere(string $alias, SmartDto $filter, SmartDto $query, QueryBuilder $queryBuilder): void {
		parent::applyWhere($alias, $filter, $query, $queryBuilder);
		$filter->knownWithValue('id') && $this->matchOf($queryBuilder, $alias, '$.id', $filter->getValue('id'));
		$filter->knownWithValue('userId') && $this->matchOf($queryBuilder, $alias, '$.userId', $filter->getValue('userId'));
		$filter->knownWithValue('params') && $this->fulltextOf($queryBuilder, $alias, '$.params', $filter->getValue('params'));
	}

	public function cleanup(): void {
		$this->native("DELETE FROM %n WHERE status = ? OR commit = true", $this->table, JobStatus::JOB_SUCCESS);
	}
}
