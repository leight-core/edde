<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File\Endpoint;

use Edde\File\Dto\GcResultDto;
use Edde\File\FileServiceTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class GcEndpoint extends AbstractMutationEndpoint {
	use FileServiceTrait;

	public function post(): GcResultDto {
		return $this->fileService->gc(true);
	}
}
