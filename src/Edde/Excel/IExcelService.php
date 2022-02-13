<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Excel\Dto\HandleDto;
use Edde\Excel\Dto\MetaDto;
use Edde\Excel\Dto\ReadDto;
use Edde\Excel\Exception\EmptySheetException;
use Edde\Excel\Exception\ExcelException;
use Edde\Excel\Exception\MissingHeaderException;
use Edde\Progress\IProgress;
use Edde\Reflection\Exception\MissingReflectionClassException;
use Edde\Reflection\Exception\UnknownTypeException;
use Generator;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use ReflectionException;

interface IExcelService {
	/**
	 * Iterate through the file cell by cell
	 *
	 * @param ReadDto $readDto
	 *
	 * @return Generator
	 */
	public function iterate(ReadDto $readDto): Generator;

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
	 * This method does not throw any exception; wrapper around "read"
	 *
	 * @param ReadDto $readDto
	 *
	 * @return Generator
	 */
	public function safeRead(ReadDto $readDto): Generator;

	/**
	 * Quite magical method which reads a metadata from Excel file and process all it's tabs.
	 *
	 * @param HandleDto      $handleDto
	 * @param IProgress|null $progress adds an ability to track a progress and eventually kill the running method if needed
	 */
	public function handle(HandleDto $handleDto, IProgress $progress = null): void;

	/**
	 * Computes a metadata for the given excel file. This method is quite heavy and likes to throw some exceptions.
	 *
	 * @param string $file
	 *
	 * @return MetaDto
	 *
	 * @throws ExcelException
	 * @throws MissingReflectionClassException
	 * @throws UnknownTypeException
	 * @throws ReflectionException
	 */
	public function meta(string $file): MetaDto;

	/**
	 * @param ReadDto $readDto
	 *
	 * @return Spreadsheet
	 */
	public function load(ReadDto $readDto): Spreadsheet;
}
