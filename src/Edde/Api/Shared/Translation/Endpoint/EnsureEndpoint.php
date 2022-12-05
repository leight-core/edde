<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Translation\Endpoint;

use Edde\Rest\Endpoint\AbstractCreateEndpoint;
use Edde\Translation\Dto\Ensure\EnsureDto;
use Edde\Translation\Mapper\ToTranslationMapperTrait;
use Edde\Translation\Repository\TranslationRepositoryTrait;

class EnsureEndpoint extends AbstractCreateEndpoint {
	use TranslationRepositoryTrait;
	use ToTranslationMapperTrait;

	public function post(EnsureDto $ensureDto) {
		return $this->toTranslationMapper->item($this->translationRepository->ensure($ensureDto));
	}
}
