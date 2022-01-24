<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File\Endpoint;

use Edde\Api\Shared\File\Dto\StaleDto;
use Edde\File\Dto\FileDto;
use Edde\File\Mapper\FileMapperTrait;
use Edde\File\Repository\FileRepositoryTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

/**
 * @description unmarks stale file (remove TTL).
 */
class RefreshEndpoint extends AbstractMutationEndpoint {
	use FileRepositoryTrait;
	use FileMapperTrait;

	public function post(StaleDto $staleDto): FileDto {
		return $this->fileMapper->item($this->fileRepository->change([
			'id'  => $staleDto->fileId,
			'ttl' => null,
		]));
	}
}
