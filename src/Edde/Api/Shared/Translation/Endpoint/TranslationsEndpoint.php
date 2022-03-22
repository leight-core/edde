<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Translation\Endpoint;

use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;
use Edde\Translation\Dto\TranslationDto;
use Edde\Translation\Dto\TranslationFilterDto;
use Edde\Translation\Dto\TranslationOrderByDto;
use Edde\Translation\Mapper\ToTranslationMapperTrait;
use Edde\Translation\Repository\TranslationRepositoryTrait;

class TranslationsEndpoint extends AbstractQueryEndpoint {
	use TranslationRepositoryTrait;
	use ToTranslationMapperTrait;

	/**
	 * @param Query<TranslationOrderByDto, TranslationFilterDto> $query
	 *
	 * @return QueryResult<TranslationDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->translationRepository->toResult($query, $this->toTranslationMapper);
	}
}
