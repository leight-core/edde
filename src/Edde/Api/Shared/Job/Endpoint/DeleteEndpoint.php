<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Endpoint;

use Edde\Job\Dto\Delete\DeleteDto;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class DeleteEndpoint extends AbstractMutationEndpoint {
	use JobRepositoryTrait;

	/**
	 * @param DeleteDto|void|null $deleteDto
	 */
	public function post(?DeleteDto $deleteDto) {
		$this->jobRepository->deleteBy($deleteDto);
	}
}
