<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Endpoint;

use Edde\Cache\CacheTrait;
use Edde\Discovery\Dto\DiscoveryIndexDto;
use Edde\Discovery\Dto\DiscoveryItemDto;
use Edde\Dto\DtoServiceTrait;
use Edde\Http\HttpIndexTrait;
use Edde\Link\LinkGeneratorTrait;
use Edde\Rest\Endpoint\AbstractFetchEndpoint;
use Edde\Rest\EndpointInfoTrait;
use Edde\Rest\Reflection\Endpoint;

/**
 * @description Endpoint used to get server discovery index.
 */
class DiscoveryEndpoint extends AbstractFetchEndpoint {
	use HttpIndexTrait;
	use DtoServiceTrait;
	use EndpointInfoTrait;
	use LinkGeneratorTrait;
	use CacheTrait;

	/**
	 * @return DiscoveryIndexDto
	 */
	public function get(): DiscoveryIndexDto {
		return $this->cache->get(DiscoveryIndexDto::class, function () {
			return $this->cache->set(DiscoveryIndexDto::class, $this->dtoService->fromArray(DiscoveryIndexDto::class, [
				'index' => array_map(function (Endpoint $endpoint) {
					return $this->dtoService->fromArray(DiscoveryItemDto::class, [
						'id'     => $this->endpointInfo->getId($endpoint->class->fqdn),
						'url'    => $this->linkGenerator->link($endpoint->link),
						'link'   => $this->linkGenerator->link($endpoint->link),
						'params' => $endpoint->query,
					]);
				}, array_combine(array_map(function (Endpoint $endpoint) {
					return $this->endpointInfo->getId($endpoint->class->fqdn);
				}, $this->httpIndex->endpoints()), array_values($this->httpIndex->endpoints()))),
			]));
		});
	}
}
