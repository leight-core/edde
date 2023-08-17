<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Dto\DtoServiceTrait;
use Edde\File\FileServiceTrait;
use Edde\Job\Async\AbstractAsyncService;
use Edde\Job\Dto\JobDto;

abstract class AbstractImportService extends AbstractAsyncService implements IImportService {
	use DtoServiceTrait;
	use FileServiceTrait;

	public function import(string $fileId): JobDto {
		return $this->async($fileId);
	}
}
