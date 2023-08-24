<?php
declare(strict_types=1);

namespace Edde\Job\Rpc;

use Edde\Dto\SmartDto;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Job\Schema\Job\JobSchema;
use Edde\Job\Schema\Job\Query\JobFilterSchema;
use Edde\Job\Schema\Job\Query\JobOrderBySchema;
use Edde\Job\Schema\Job\Query\JobQuerySchema;
use Edde\Rpc\AbstractRpcHandler;

class JobFindByRpcHandler extends AbstractRpcHandler {
    use JobRepositoryTrait;

    protected $requestSchema = JobQuerySchema::class;
    protected $responseSchema = JobSchema::class;
    protected $orderBySchema = JobOrderBySchema::class;
    protected $filterSchema = JobFilterSchema::class;

    public function handle(SmartDto $request) {
        return $this->jobRepository->findBy(
            $request->merge(
                [
                    'filter' => [
                        'userId' => $this->currentUserService->requiredId(),
                    ],
                ],
                true
            )
        );
    }
}
