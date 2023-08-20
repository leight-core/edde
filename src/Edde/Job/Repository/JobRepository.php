<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use Cake\Database\Query;
use Edde\Database\Repository\AbstractRepository;
use Edde\Dto\SmartDto;
use Edde\Job\Schema\Job\Query\JobQuerySchema;
use Edde\Job\Schema\JobStatus;

class JobRepository extends AbstractRepository {
	use JobLogRepositoryTrait;

	public function __construct() {
		parent::__construct();
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

	protected function applyWhere(SmartDto $filter, SmartDto $query, Query $builder): void {
		parent::applyWhere($filter, $query, $builder);
		$filter->knownWithValue('id') && $this->matchOf($builder, '$.id', $filter->getValue('id'));
		$filter->knownWithValue('userId') && $this->matchOf($builder, '$.userId', $filter->getValue('userId'));
		$filter->knownWithValue('params') && $this->fulltextOf($builder, '$.params', $filter->getValue('params'));
	}

	public function cleanup(): void {
		$this->deleteWith(
			$this->smartService->from(
				[
					'filter' => [
						'status' => JobStatus::JOB_SUCCESS,
					],
				],
				JobQuerySchema::class
			)
		);
	}
}
