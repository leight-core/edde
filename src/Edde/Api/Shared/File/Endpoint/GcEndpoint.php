<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File\Endpoint;

use Edde\Dto\SmartDto;
use Edde\File\FileGcAsyncServiceTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class GcEndpoint extends AbstractMutationEndpoint {
	use FileGcAsyncServiceTrait;

	public function post(): SmartDto {
		return $this->fileGcAsyncService->async();
	}
}
