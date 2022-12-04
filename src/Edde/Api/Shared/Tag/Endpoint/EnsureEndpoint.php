<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Tag\Endpoint;

use Edde\Rest\Endpoint\AbstractCreateEndpoint;
use Edde\Tag\Dto\EnsureTagDto;
use Edde\Tag\Mapper\TagMapperTrait;
use Edde\Tag\Repository\TagRepositoryTrait;

class EnsureEndpoint extends AbstractCreateEndpoint {
	use TagRepositoryTrait;
	use TagMapperTrait;

	public function post(EnsureTagDto $ensureTagDto) {
		return $this->tagMapper->item($this->tagRepository->useEnsure($ensureTagDto));
	}
}
