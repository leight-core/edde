<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Dto\DtoServiceTrait;
use Edde\Excel\Dto\ReadDto;
use Edde\Phpunit\AbstractTestCase;

class ExcelServiceTest extends AbstractTestCase {
	use ExcelServiceTrait;
	use DtoServiceTrait;

	public function testEmptyFile() {
		$generator = $this->excelService->read($this->dtoService->fromArray(ReadDto::class, [
			'file' => __DIR__ . '/../fixtures/empty-file.xlsx',
		]));
		foreach ($generator as $item) {
		}
	}
}
