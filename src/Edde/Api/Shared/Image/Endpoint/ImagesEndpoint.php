<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Image\Endpoint;

use Edde\Image\Mapper\ImageMapperTrait;
use Edde\Image\Repository\ImageRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;

class ImagesEndpoint extends AbstractEndpoint {
	use ImageRepositoryTrait;
	use ImageMapperTrait;

	public function post(Query $query) {
		return $this->imageMapper->map($this->imageRepository->query($query));
	}
}
