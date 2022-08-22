<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Tag\Endpoint;

use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;
use Edde\Tag\Mapper\TagMapperTrait;
use Edde\Tag\Repository\TagRepositoryTrait;

class TagsEndpoint extends AbstractEndpoint {
	use TagRepositoryTrait;
	use TagMapperTrait;

	public function post(Query $query) {
		return $this->tagMapper->map($this->tagRepository->query($query));
	}
}
