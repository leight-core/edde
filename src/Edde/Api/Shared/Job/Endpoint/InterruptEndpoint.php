<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Endpoint;

use Edde\Job\Dto\Interrupt\InterruptDto;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class InterruptEndpoint extends AbstractMutationEndpoint {
	use JobRepositoryTrait;

	/**
	 * @param InterruptDto|void|null $interruptDto
	 */
	public function post(?InterruptDto $interruptDto) {
		$this->jobRepository->interruptBy($interruptDto);
	}
}
