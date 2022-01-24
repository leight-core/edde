<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Endpoint;

use Edde\Job\Dto\Commit\CommitDto;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class CommitEndpoint extends AbstractMutationEndpoint {
	use JobRepositoryTrait;

	/**
	 * @param CommitDto|void|null $commitDto
	 */
	public function post(?CommitDto $commitDto) {
		$this->jobRepository->commitBy($commitDto);
	}
}
