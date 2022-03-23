<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Translation\Endpoint;

use Edde\Rest\Endpoint\AbstractDeleteEndpoint;
use Edde\Translation\Repository\TranslationRepositoryTrait;

class DropEndpoint extends AbstractDeleteEndpoint {
	use TranslationRepositoryTrait;

	public function delete() {
		$this->translationRepository->deleteWhere()->execute();
	}
}
