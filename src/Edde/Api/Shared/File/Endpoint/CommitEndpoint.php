<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File\Endpoint;

use Edde\File\Dto\CommitDto;
use Edde\File\Dto\FileDto;
use Edde\File\FileChunkServiceTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class CommitEndpoint extends AbstractMutationEndpoint {
	use FileChunkServiceTrait;

	public function post(CommitDto $commitDto): FileDto {
		return $this->fileChunkService->commit($commitDto);
	}
}
