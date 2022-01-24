<?php
declare(strict_types=1);

namespace Edde\Api\Root\Config\Endpoint;

use Edde\Config\Dto\ConfigDto;
use Edde\Config\Dto\ConfigFilterDto;
use Edde\Config\Dto\ConfigOrderByDto;
use Edde\Config\Mapper\ConfigMapperTrait;
use Edde\Config\Repository\ConfigRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Query\QueryServiceTrait;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;

/**
 * @description Returns page of configs.
 */
class ConfigsEndpoint extends AbstractQueryEndpoint {
	use QueryServiceTrait;
	use ConfigRepositoryTrait;
	use ConfigMapperTrait;

	/**
	 * @param Query<ConfigOrderByDto, ConfigFilterDto> $query
	 *
	 * @return QueryResult<ConfigDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->configRepository->toResult($query, $this->configMapper);
	}
}
