<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Container\ContainerTrait;
use Edde\Job\Dto\JobDto;
use Edde\Job\JobExecutorTrait;

class ImportManager implements IImportManager {
	use JobExecutorTrait;
	use ContainerTrait;

	public function import(string $service, string $fileId): JobDto {
		return $this->jobExecutor->execute(
			$this->container->get($service),
			$fileId
		);
	}
}
