<?php
declare(strict_types=1);

namespace Edde\Job\Rpc;

use Edde\Dto\SmartDto;
use Edde\Job\Schema\Job\JobSchema;
use Edde\Job\Service\JobServiceTrait;
use Edde\Query\Schema\WithIdentitySchema;
use Edde\Rpc\AbstractRpcHandler;

class JobFetchRpcService extends AbstractRpcHandler {
	use JobServiceTrait;

	protected $requestSchema = WithIdentitySchema::class;
	protected $responseSchema = JobSchema::class;
	protected $isFetch = true;

	public function handle(SmartDto $request) {
		return $this->jobService->find($request->getValue('id'));
	}
}
