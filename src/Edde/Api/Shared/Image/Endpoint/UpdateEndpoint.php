<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Image\Endpoint;

use Edde\Image\ImageAsyncServiceTrait;
use Edde\Job\Dto\JobDto;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class UpdateEndpoint extends AbstractMutationEndpoint {
	use ImageAsyncServiceTrait;

	public function post(): JobDto {
		return $this->imageAsyncService->async();
	}
}
