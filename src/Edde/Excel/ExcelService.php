<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Excel\Dto\ReadDto;
use Edde\Excel\Exception\EmptySheetException;
use Edde\Excel\Exception\MissingHeaderException;
use Edde\Log\LoggerTrait;
use Generator;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use function json_encode;

class ExcelService implements IExcelService {
	use LoggerTrait;

	/**
	 * @inheritdoc
	 */
	public function read(ReadDto $readDto): Generator {
		$spreadsheet = $this->load($readDto);
		if (($worksheet = $spreadsheet->getSheet($readDto->worksheet))->getHighestRow() === 1) {
			throw new EmptySheetException(sprintf('Sheet [%d] of [%s] of Excel file [%s] is empty.', $readDto->worksheet, json_encode($readDto->sheets), $readDto->file));
		}
		/** @var $header Row */
		if (!($header = (iterator_to_array($worksheet->getRowIterator(1, 1))[1] ?? null))) {
			throw new MissingHeaderException(sprintf('Excel file [%s] does not have a header (is the file OK?).', $readDto->file));
		}
		$header = array_map(function (Cell $cell) {
			return $cell->getValue();
		}, iterator_to_array($header->getCellIterator()));

		foreach ($worksheet->getRowIterator($readDto->skip + 1) as $index => $row) {
			$item = [];
			foreach ($row->getCellIterator() as $cell) {
				$item[$header[$cell->getColumn()]] = $cell->getFormattedValue();
			}
			yield str_pad((string)$index, 8, '0', STR_PAD_LEFT) => $item;
		}
	}

	/**
	 * @param ReadDto $readDto
	 *
	 * @return Spreadsheet
	 *
	 * @throws Exception
	 */
	protected function load(ReadDto $readDto): Spreadsheet {
		$reader = IOFactory::createReaderForFile($readDto->file);
		$reader->setLoadSheetsOnly($readDto->sheets);
		return $reader->load($readDto->file);
	}
}
