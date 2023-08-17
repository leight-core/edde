<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Dto\DtoServiceTrait;
use Edde\Dto\SmartDto;
use Edde\File\FileServiceTrait;
use Edde\Job\Async\AbstractAsyncService;

abstract class AbstractImportService extends AbstractAsyncService implements IImportService {
	use DtoServiceTrait;
	use FileServiceTrait;

	public function import(string $fileId): SmartDto {
		return $this->async($fileId);
	}
}
