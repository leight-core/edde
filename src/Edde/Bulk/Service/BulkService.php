<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

use DateTime;
use Edde\Bulk\Mapper\BulkDtoMapperTrait;
use Edde\Bulk\Repository\BulkRepositoryTrait;
use Edde\Dto\SmartDto;
use Edde\User\CurrentUserServiceTrait;

class BulkService {
	use CurrentUserServiceTrait;
	use BulkRepositoryTrait;
	use BulkDtoMapperTrait;

	public function create(SmartDto $request) {
		return $this->bulkDtoMapper->item(
			$this->bulkRepository->save(
				$request->merge([
					'created' => new DateTime(),
					'userId'  => $this->currentUserService->requiredId(),
				])
			)
		);
	}
}
