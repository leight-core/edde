<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Image\Endpoint;

use Edde\Dto\SmartDto;
use Edde\Image\ImageAsyncServiceTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class UpdateEndpoint extends AbstractMutationEndpoint {
	use ImageAsyncServiceTrait;

	public function post(): SmartDto {
		return $this->imageAsyncService->async();
	}
}
