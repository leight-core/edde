<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Endpoint;

use Edde\Api\Shared\Dto\LanguageDto;
use Edde\Dto\DtoServiceTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;
use Edde\Translation\LanguageServiceTrait;
use Edde\Translation\Repository\TranslationRepositoryTrait;

/**
 * @description Get all available languages in the application.
 */
class LanguageListEndpoint extends AbstractQueryEndpoint {
	use TranslationRepositoryTrait;
	use DtoServiceTrait;
	use LanguageServiceTrait;

	/**
	 * @param Query $query
	 *
	 * @return QueryResult<LanguageDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->queryService->toResponse($this->languageService->toList());
	}
}
