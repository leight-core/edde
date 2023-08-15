<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

use DateTime;
use Edde\Bulk\Mapper\BulkDtoMapperTrait;
use Edde\Bulk\Repository\BulkRepositoryTrait;
use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\User\CurrentUserServiceTrait;

class BulkService {
	use CurrentUserServiceTrait;
	use BulkRepositoryTrait;
	use BulkDtoMapperTrait;
	use SmartServiceTrait;

	public function create(SmartDto $request) {
		return $this->bulkDtoMapper->item(
			$this->bulkRepository->save(
				$this->smartService->cloneTo($request, BulkSchema::class, (object)[
					'created' => new DateTime(),
					'userId'  => $this->currentUserService->requiredId(),
				])
			)
		);
	}
}