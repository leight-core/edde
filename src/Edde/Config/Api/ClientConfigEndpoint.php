<?php
declare(strict_types=1);

namespace Edde\Config\Api;

use Edde\Config\ClientConfigServiceTrait;
use Edde\Config\Dto\ClientConfigDto;
use Edde\Rest\Endpoint\AbstractFetchEndpoint;

/**
 * @description Provides access to the client side configuration.
 * @link        /client.json
 */
class ClientConfigEndpoint extends AbstractFetchEndpoint {
	use ClientConfigServiceTrait;

	public function get(): ClientConfigDto {
		return $this->clientConfigService->config();
	}
}
