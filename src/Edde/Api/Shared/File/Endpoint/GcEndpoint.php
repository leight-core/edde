<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File\Endpoint;

use Edde\File\FileGcServiceTrait;
use Edde\Job\Dto\JobDto;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class GcEndpoint extends AbstractMutationEndpoint {
	use FileGcServiceTrait;

	public function post(): JobDto {
		return $this->fileGcService->async();
	}
}
