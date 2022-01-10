<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Dto\DtoServiceTrait;
use Edde\Excel\Dto\HandleDto;
use Edde\Excel\Dto\MetaDto;
use Edde\Excel\Dto\ReadDto;
use Edde\Excel\Dto\ServiceDto;
use Edde\Excel\Dto\TabDto;
use Edde\Excel\Exception\EmptySheetException;
use Edde\Excel\Exception\MissingHeaderException;
use Edde\Log\LoggerTrait;
use Generator;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use Throwable;
use function array_combine;
use function array_map;
use function array_merge;
use function array_unique;
use function explode;
use function iterator_to_array;
use function json_encode;

class ExcelService implements IExcelService {
	use LoggerTrait;
	use DtoServiceTrait;

	/**
	 * @inheritdoc
	 */
	public function read(ReadDto $readDto): Generator {
		$spreadsheet = $this->load($readDto);
		if (($worksheet = $spreadsheet->getSheet($readDto->worksheet))->getHighestRow() === 1) {
			throw new EmptySheetException(sprintf('Sheet [%d] of [%s] of Excel file [%s] is empty.', $readDto->worksheet, json_encode($readDto->sheets ?? 'default'), $readDto->file));
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

	public function safeRead(ReadDto $readDto): Generator {
		try {
			yield from $this->read($readDto);
		} catch (Throwable $exception) {
		}
	}

	public function handle(HandleDto $handleDto): void {
		$meta = $this->meta($handleDto->file);
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

	/**
	 * @param string $file
	 *
	 * @return MetaDto
	 */
	protected function meta(string $file): MetaDto {
		$tabs = [];
		$services = [];
		foreach ($this->safeRead($this->dtoService->fromArray(ReadDto::class, [
			'file'   => $file,
			'sheets' => 'tabs',
		])) as $tab) {
			$tabs[] = $this->dtoService->fromArray(TabDto::class, [
				'name'     => $tab['tab'],
				'services' => $services = array_merge($services, array_map('trim', explode(',', $tab['services']))),
			]);
		}
		$services = array_unique($services);
		$translations = [];
		foreach ($this->safeRead($this->dtoService->fromArray(ReadDto::class, [
			'file'   => $file,
			'sheets' => 'translations',
		])) as $translation) {
			$translations[$translation['from']] = $translation['to'];
		}

		return $this->dtoService->fromArray(MetaDto::class, [
			'file'         => $file,
			'tabs'         => $tabs,
			'translations' => $translations,
			'services'     => array_combine($services, array_map(function (string $service) use ($file, $translations) {
				$items = [];
				if ($key = $translations[$service . '.translations'] ?? null) {
					foreach ($this->safeRead($this->dtoService->fromArray(ReadDto::class, [
						'file'   => $file,
						'sheets' => $key,
					])) as $translation) {
						$items[$translation['from']] = $translation['to'];
					}
				}
				return $this->dtoService->fromArray(ServiceDto::class, [
					'name'         => $service,
					'translations' => $items,
				]);
			}, $services)),
		]);
	}
}
