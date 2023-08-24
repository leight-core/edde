<?php
declare(strict_types=1);

namespace Edde\Job\Rpc;

use Edde\Dto\SmartDto;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Job\Schema\Job\Query\JobQuerySchema;
use Edde\Rpc\AbstractRpcHandler;

class JobCountRpcHandler extends AbstractRpcHandler {
    use JobRepositoryTrait;

    protected $requestSchema = JobQuerySchema::class;

    public function handle(SmartDto $request) {
        return $this->jobRepository->total(
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
