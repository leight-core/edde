<?php
declare(strict_types=1);

namespace Edde\Api\Root\Config\Endpoint;

use Dibi\Exception;
use Edde\Config\Dto\ConfigDto;
use Edde\Config\Dto\Create\CreateDto;
use Edde\Config\Mapper\ConfigMapperTrait;
use Edde\Config\Repository\ConfigRepositoryTrait;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\Rest\Endpoint\AbstractCreateEndpoint;
use Throwable;

/**
 * @description Creates a new configuration item.
 */
class CreateEndpoint extends AbstractCreateEndpoint {
	use ConfigRepositoryTrait;
	use ConfigMapperTrait;

	/**
	 * @param CreateDto $createDto
	 *
	 * @return ConfigDto
	 *
	 * @throws Exception
	 * @throws ItemException
	 * @throws SkipException
	 * @throws Throwable
	 */
	public function post(CreateDto $createDto): ConfigDto {
		return $this->configMapper->item($this->configRepository->create($createDto));
	}
}
