<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Excel\Dto\ReadDto;
use Edde\Excel\Exception\EmptySheetException;
use Edde\Excel\Exception\MissingHeaderException;
use Generator;
use PhpOffice\PhpSpreadsheet\Exception;

interface IExcelService {
	/**
	 * Iterate through the given file on the given worksheet.
	 *
	 * @return Generator|array[]
	 *
	 * @throws EmptySheetException
	 * @throws Exception
	 * @throws MissingHeaderException
	 */
	public function read(ReadDto $readDto): Generator;
}
