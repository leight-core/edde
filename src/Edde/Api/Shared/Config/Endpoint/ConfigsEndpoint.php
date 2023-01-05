<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Config\Endpoint;

use Edde\Config\Mapper\ConfigMapperTrait;
use Edde\Config\Repository\ConfigRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;

/**
 * @description Returns page of configs.
 */
class ConfigsEndpoint extends AbstractEndpoint {
	use ConfigRepositoryTrait;
	use ConfigMapperTrait;

	public function post(Query $query) {
		$query->filter->private = false;
		return $this->configMapper->map($this->configRepository->query($query));
	}
}
