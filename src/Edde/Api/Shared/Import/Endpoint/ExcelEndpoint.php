<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Import\Endpoint;

use Edde\Dto\SmartDto;
use Edde\Excel\ExcelImportServiceTrait;
use Edde\Excel\IExcelImportService;
use Edde\File\Exception\FileNotReadableException;
use Edde\File\FileServiceTrait;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;
use Edde\Rest\Exception\RestException;
use Edde\Uuid\UuidServiceTrait;
use Throwable;

class ExcelEndpoint extends AbstractMutationEndpoint {
	use FileServiceTrait;
	use UuidServiceTrait;
	use ExcelImportServiceTrait;

	/**
	 * @return JobDto
	 *
	 * @throws Exception
	 * @throws FileNotReadableException
	 * @throws ItemException
	 * @throws RestException
	 * @throws SkipException
	 * @throws Throwable
	 */
	public function post(): SmartDto {
		return $this->excelImportService->import(
			$this->fileService->accept(
				'file',
				'/import/' . IExcelImportService::class,
				$this->uuidService->uuid4() . '-' . ($_FILES['file']['name'] ?? 'unknown'),
				/**
				 * 7 days - when for example some import will be failing, with this one can access the file; later on GC will collect the old ones.
				 */
				3600 * 24 * 7
			)->id
		);
	}
}
