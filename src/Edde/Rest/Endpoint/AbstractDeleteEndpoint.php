<?php
declare(strict_types=1);

namespace Edde\Rest\Endpoint;

use Edde\Mapper\IMapper;
use Edde\Repository\IRepository;

abstract class AbstractDeleteEndpoint extends AbstractMutationEndpoint {
	protected function remove(IMapper $mapper, IRepository $repository, string $param) {
		return $mapper->item($repository->delete($this->param($param)));
	}
}
