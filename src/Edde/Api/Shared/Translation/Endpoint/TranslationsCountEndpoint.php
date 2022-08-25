<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Translation\Endpoint;

use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;
use Edde\Translation\Mapper\ToTranslationMapperTrait;
use Edde\Translation\Repository\TranslationRepositoryTrait;

/**
 * @alterLink /translations/count
 */
class TranslationsCountEndpoint extends AbstractEndpoint {
	use TranslationRepositoryTrait;
	use ToTranslationMapperTrait;

	public function post(Query $query) {
		return $this->translationRepository->total($query);
	}
}
