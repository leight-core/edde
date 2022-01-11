<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Dto\DtoServiceTrait;
use Edde\Excel\Dto\HandleDto;
use Edde\Excel\Dto\ReadDto;
use Edde\Excel\Exception\EmptySheetException;
use Edde\Import\Importer\ImportProgress;
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
		$progress = $this->container->injectOn(new ImportProgress());
		$meta = $this->excelService->meta($file = __DIR__ . '/../fixtures/complex-import.xlsx');

		$this->assertCount(4, $meta->tabs);
		$this->assertCount(5, $meta->services);
		$this->assertEquals(20, $meta->tabs[0]->count);
		$this->assertEquals(6, $meta->tabs[1]->count);
		$this->assertEquals(15, $meta->tabs[2]->count);
		$this->assertEquals(0, $meta->tabs[3]->count);

		$this->excelService->handle($this->dtoService->fromArray(HandleDto::class, [
			'file' => $file,
		]), $progress);
		$this->assertEquals(61, $progress->total);
		$this->assertEquals(60, $progress->success);
		$this->assertEquals(1, $progress->error);
		$this->assertEquals(100, $progress->progress);
	}
}
