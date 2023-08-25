<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use Cake\Database\Query;
use Edde\Database\Repository\AbstractRepository;
use Edde\Dto\SmartDto;
use Edde\Job\Schema\Job\JobSchema;
use Edde\Job\Schema\Job\Query\JobQuerySchema;
use Edde\Job\Schema\JobStatus;

class JobRepository extends AbstractRepository {
    use JobLogRepositoryTrait;

    public function __construct() {
        parent::__construct(JobSchema::class);
        $this->orderBy = [
            '$.started' => 'desc',
        ];
        $this->fulltextOf = [
            'id'      => '$.id',
            'service' => '$.service',
            'params'  => '$.params',
        ];
        $this->matchOf = [
            'id'        => '$.id',
            'reference' => '$.reference',
            'userId'    => '$.user_id',
            'status'    => '$.status',
            'service'   => '$.service',
        ];
        $this->matchOfIn = [
            'statusIn'  => '$.status',
            'serviceIn' => '$.service',
        ];
    }

    protected function applyWhere(SmartDto $filter, SmartDto $query, Query $builder): void {
        parent::applyWhere($filter, $query, $builder);
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
