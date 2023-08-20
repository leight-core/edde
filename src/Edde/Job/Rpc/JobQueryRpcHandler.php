<?php
declare(strict_types=1);

namespace Edde\Job\Rpc;

use Edde\Dto\SmartDto;
use Edde\Job\Schema\Job\JobFilterSchema;
use Edde\Job\Schema\Job\JobOrderBySchema;
use Edde\Job\Schema\Job\JobQuerySchema;
use Edde\Job\Schema\Job\JobSchema;
use Edde\Job\Service\JobServiceTrait;
use Edde\Rpc\AbstractRpcHandler;

class JobQueryRpcHandler extends AbstractRpcHandler {
	use JobServiceTrait;

	protected $requestSchema = JobQuerySchema::class;
	protected $responseSchema = JobSchema::class;
	protected $orderBySchema = JobOrderBySchema::class;
	protected $filterSchema = JobFilterSchema::class;
	protected $responseSchemaArray = true;
	protected $isQuery = true;

	public function handle(SmartDto $request) {
		return $this->jobService->query($request);
	}
}
