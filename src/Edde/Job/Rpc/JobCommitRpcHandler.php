<?php
declare(strict_types=1);

namespace Edde\Job\Rpc;

use Edde\Dto\SmartDto;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Job\Schema\Job\JobSchema;
use Edde\Job\Service\JobServiceTrait;
use Edde\Query\Schema\WithIdentitySchema;
use Edde\Rpc\AbstractRpcHandler;

class JobCommitRpcHandler extends AbstractRpcHandler {
    use JobServiceTrait;

    use JobRepositoryTrait;

    protected $requestSchema = WithIdentitySchema::class;
    protected $responseSchema = JobSchema::class;
    protected $isMutator = true;

    public function handle(SmartDto $request) {
        return $this->jobService->commit($request);
    }
}
