<?php
declare(strict_types=1);

namespace Edde\Api\Root\Config\Endpoint;

use Edde\Config\Dto\ConfigDto;
use Edde\Config\Dto\Patch\PatchDto;
use Edde\Config\Mapper\ConfigMapperTrait;
use Edde\Config\Repository\ConfigRepositoryTrait;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\Repository\Exception\RepositoryException;
use Edde\Rest\Endpoint\AbstractPatchEndpoint;
use Throwable;

/**
 * @description Updates a config item.
 */
class PatchEndpoint extends AbstractPatchEndpoint {
	use ConfigRepositoryTrait;
	use ConfigMapperTrait;

	/**
	 * @param PatchDto $patchDto
	 *
	 * @return ConfigDto
	 *
	 * @throws ItemException
	 * @throws SkipException
	 * @throws RepositoryException
	 * @throws Throwable
	 */
	public function patch(PatchDto $patchDto): ConfigDto {
		return $this->configMapper->item($this->configRepository->patch([
			'id'    => $patchDto->id,
			'key'   => $patchDto->config->key,
			'value' => $patchDto->config->value,
		]));
	}
}
