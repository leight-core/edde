<?php
declare(strict_types=1);

namespace Edde\Api\Root\Log\Endpoint;

use Edde\Log\Mapper\LogMapperTrait;
use Edde\Log\Repository\LogRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;

/**
 * @description Page through system logs.
 */
class LogsEndpoint extends AbstractEndpoint {
	use LogRepositoryTrait;
	use LogMapperTrait;

	public function post(Query $query) {
		return $this->logMapper->map($this->logRepository->query($query));
	}
}
