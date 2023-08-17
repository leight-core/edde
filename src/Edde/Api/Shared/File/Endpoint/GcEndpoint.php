<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File\Endpoint;

use Edde\File\FileGcAsyncServiceTrait;
use Edde\Job\Dto\JobDto;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class GcEndpoint extends AbstractMutationEndpoint {
	use FileGcAsyncServiceTrait;

	public function post(): JobDto {
		return $this->fileGcAsyncService->async();
	}
}
