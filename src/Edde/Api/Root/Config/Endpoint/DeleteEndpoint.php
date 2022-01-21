<?php
declare(strict_types=1);

namespace Edde\Api\Root\Config\Endpoint;

use Edde\Config\Dto\ConfigDto;
use Edde\Config\Mapper\ConfigMapperTrait;
use Edde\Config\Repository\ConfigRepositoryTrait;
use Edde\Rest\Endpoint\AbstractDeleteEndpoint;

/**
 * @description Delete a config item.
 * @query       configId
 */
class DeleteEndpoint extends AbstractDeleteEndpoint {
	use ConfigMapperTrait;
	use ConfigRepositoryTrait;

	/**
	 * @return ConfigDto
	 */
	public function delete(): ConfigDto {
		return $this->remove($this->configMapper, $this->configRepository, 'configId');
	}
}
