<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Excel\Exception\EmptySheetException;
use Edde\Excel\Exception\MissingHeaderException;
use Edde\Log\LoggerTrait;
use Generator;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class ExcelService {
	use LoggerTrait;

	/**
	 * @param string     $file   file name to be loaded
	 * @param array|null $sheets optionally provide array of requested sheets from the excel file
	 *
	 * @return Spreadsheet
	 *
	 * @throws Exception
	 */
	public function load(string $file, array $sheets = null): Spreadsheet {
		$reader = IOFactory::createReaderForFile($file);
		$reader->setLoadSheetsOnly($sheets);
		return $reader->load($file);
	}

	/**
	 * Iterate through the given file on the given worksheet.
	 *
	 * @param string     $file      file to load, yaaay!
	 * @param int        $worksheet worksheet index to be iterated
	 * @param int        $first     first line (0 - without header, 1 - with a header) - basically skips first number of rows, indexed from 0
	 * @param mixed|null $sheet     sheet to load (one can save some perf. by explicitly saying sheet name)
	 *
	 * @return Generator|array[]
	 *
	 * @throws EmptySheetException
	 * @throws Exception
	 * @throws MissingHeaderException
	 */
	public function read(string $file, int $worksheet = 0, int $first = 1, $sheet = null): Generator {
		$spreadsheet = $this->load($file, $sheet ? [$sheet] : null);
		if (($worksheet = $spreadsheet->getSheet($worksheet))->getHighestRow() === 1) {
			throw new EmptySheetException(sprintf('Sheet [%s] of Excel file is empty.', $sheet));
		}
		/** @var $header Row */
		if (!($header = (iterator_to_array($worksheet->getRowIterator(1, 1))[1] ?? null))) {
			throw new MissingHeaderException(sprintf('Excel file [%s] does not have a header (is the file OK?).', $file));
		}
		$header = array_map(function (Cell $cell) {
			return $cell->getValue();
		}, iterator_to_array($header->getCellIterator()));

		foreach ($worksheet->getRowIterator($first + 1) as $index => $row) {
			$item = [];
			foreach ($row->getCellIterator() as $cell) {
				$item[$header[$cell->getColumn()]] = $cell->getFormattedValue();
			}
			yield str_pad((string)$index, 8, '0', STR_PAD_LEFT) => $item;
		}
	}
}
