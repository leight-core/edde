<?php
declare(strict_types=1);

namespace PuffSmith\Api\Shared\Endpoint;

use Edde\Cache\DatabaseCacheTrait;
use Edde\Discovery\Dto\DiscoveryIndexDto;
use Edde\Discovery\Dto\DiscoveryItemDto;
use Edde\Dto\DtoServiceTrait;
use Edde\Http\HttpIndexTrait;
use Edde\Link\LinkGeneratorTrait;
use Edde\Rest\Endpoint\AbstractFetchEndpoint;
use Edde\Rest\EndpointInfoTrait;
use Edde\Rest\Reflection\Endpoint;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * @description Endpoint used to get server discovery index.
 */
class DiscoveryEndpoint extends AbstractFetchEndpoint {
	use HttpIndexTrait;
	use DatabaseCacheTrait;
	use DtoServiceTrait;
	use EndpointInfoTrait;
	use LinkGeneratorTrait;

	/**
	 * @return DiscoveryIndexDto
	 *
	 * @throws InvalidArgumentException
	 */
	public function get(): DiscoveryIndexDto {
		return $this->databaseCache->get(DiscoveryIndexDto::class, function (string $key) {
			$this->databaseCache->set($key, $value = $this->dtoService->fromArray(DiscoveryIndexDto::class, [
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
			return $value;
		});
	}
}
