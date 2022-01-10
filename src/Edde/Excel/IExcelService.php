<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Excel\Dto\HandleDto;
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

	/**
	 * Quite magical method which reads a metadata from Excel file and process all it's tabs.
	 *
	 * @param HandleDto $handleDto
	 */
	public function handle(HandleDto $handleDto): void;
}
