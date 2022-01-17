<?php
declare(strict_types=1);

namespace Edde\Config;

use Edde\Config\Dto\ClientConfigDto;
use Edde\Dto\DtoServiceTrait;
use Edde\Link\LinkGeneratorTrait;

class ClientConfigService {
	use LinkGeneratorTrait;
	use DtoServiceTrait;

	public function config(): ClientConfigDto {
		return $this->dtoService->fromArray(ClientConfigDto::class, [
			'discovery' => $this->linkGenerator->link('/api/shared/discovery'),
		]);
	}
}
