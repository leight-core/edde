<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Dto\DtoServiceTrait;
use Edde\Excel\Dto\HandleDto;
use Edde\Excel\Dto\ReadDto;
use Edde\Excel\Exception\EmptySheetException;
use Edde\Phpunit\AbstractTestCase;
use PhpOffice\PhpSpreadsheet\Exception;
use function iterator_to_array;

class ExcelServiceTest extends AbstractTestCase {
	use ExcelServiceTrait;
	use DtoServiceTrait;

	/**
	 * @throws EmptySheetException
	 * @throws Exception\MissingHeaderException
	 * @throws Exception
	 */
	public function testEmptyFile() {
		$this->expectException(EmptySheetException::class);
		$generator = $this->excelService->read($this->dtoService->fromArray(ReadDto::class, [
			'file' => __DIR__ . '/../fixtures/empty-file.xlsx',
		]));
		iterator_to_array($generator);
	}

	public function testHandleComplex() {
		$this->excelService->handle($this->dtoService->fromArray(HandleDto::class, [
			'file' => __DIR__ . '/../fixtures/complex-import.xlsx',
		]));
	}
}
