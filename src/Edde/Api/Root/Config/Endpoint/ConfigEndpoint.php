<?php
declare(strict_types=1);

namespace Edde\Api\Root\Config\Endpoint;

use Dibi\Exception;
use Edde\Config\Dto\ConfigDto;
use Edde\Config\Mapper\ConfigMapperTrait;
use Edde\Config\Repository\ConfigRepositoryTrait;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\Rest\Endpoint\AbstractFetchEndpoint;
use Edde\Rest\Exception\RestException;

/**
 * @description Fetch config item by it's uuid.
 * @query       configId
 */
class ConfigEndpoint extends AbstractFetchEndpoint {
	use ConfigRepositoryTrait;
	use ConfigMapperTrait;

	/**
	 * @return ConfigDto
	 *
	 * @throws Exception
	 * @throws ItemException
	 * @throws SkipException
	 * @throws RestException
	 */
	public function get(): ConfigDto {
		return $this->configMapper->item($this->configRepository->find($this->param('configId')));
	}
}
