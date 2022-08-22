<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Endpoint;

use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;
use Edde\Translation\LanguageServiceTrait;
use Edde\Translation\Repository\TranslationRepositoryTrait;

/**
 * @description Get all available languages in the application.
 */
class LanguageListEndpoint extends AbstractEndpoint {
	use TranslationRepositoryTrait;
	use LanguageServiceTrait;

	public function post(Query $query) {
		return $this->languageService->toList();
	}
}
