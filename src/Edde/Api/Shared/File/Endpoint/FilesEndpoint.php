<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File\Endpoint;

use Edde\File\Mapper\FileMapperTrait;
use Edde\File\Repository\FileRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;

class FilesEndpoint extends AbstractEndpoint {
	use FileRepositoryTrait;
	use FileMapperTrait;

	public function post(Query $query) {
		return $this->fileMapper->map($this->fileRepository->query($query));
	}
}
