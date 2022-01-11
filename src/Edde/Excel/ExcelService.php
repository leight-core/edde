<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Container\ContainerTrait;
use Edde\Dto\DtoServiceTrait;
use Edde\Excel\Dto\HandleDto;
use Edde\Excel\Dto\MetaDto;
use Edde\Excel\Dto\ReadDto;
use Edde\Excel\Dto\ServiceDto;
use Edde\Excel\Dto\TabDto;
use Edde\Excel\Exception\EmptySheetException;
use Edde\Excel\Exception\ExcelException;
use Edde\Excel\Exception\MissingHeaderException;
use Edde\Log\LoggerTrait;
use Edde\Progress\IProgress;
use Edde\Progress\NoProgress;
use Edde\Reader\IReader;
use Edde\Reflection\Dto\Method\IRequestMethod;
use Edde\Reflection\Dto\Parameter\ClassParameter;
use Edde\Reflection\Exception\MissingReflectionClassException;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Reflection\ReflectionServiceTrait;
use Generator;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use ReflectionException;
use Throwable;
use function array_combine;
use function array_filter;
use function array_map;
use function array_merge;
use function array_unique;
use function explode;
use function is_string;
use function iterator_count;
use function iterator_to_array;
use function json_encode;

class ExcelService implements IExcelService {
	use LoggerTrait;
	use DtoServiceTrait;
	use ContainerTrait;
	use ReflectionServiceTrait;

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
		$header = array_map(function (Cell $cell) use ($readDto) {
			return $readDto->translations[$cell->getValue()] ?? $cell->getValue();
		}, iterator_to_array($header->getCellIterator()));

		foreach ($worksheet->getRowIterator($readDto->skip + 1) as $index => $row) {
			$item = [];
			foreach ($row->getCellIterator() as $cell) {
				$item[$header[$cell->getColumn()]] = $cell->getFormattedValue();
			}
			yield str_pad((string)$index, 8, '0', STR_PAD_LEFT) => array_filter($item, static function ($item) {
				return !(is_string($item) && empty($item));
			});
		}
	}

	public function safeRead(ReadDto $readDto): Generator {
		try {
			yield from $this->read($readDto);
		} catch (Throwable $exception) {
		}
	}

	/**
	 * @inheritdoc
	 */
	public function handle(HandleDto $handleDto, IProgress $progress = null): void {
		$progress = NoProgress::ensure($progress);
		$meta = $this->meta($handleDto->file);
		$progress->check();
		$progress->onStart($meta->total);
		foreach ($meta->tabs as $tab) {
			foreach ($tab->services as $service) {
				/** @var $reader IReader */
				$reader = $this->container->get($service);
				$reader->read($this->safeRead($this->dtoService->fromArray(ReadDto::class, [
					'file'         => $handleDto->file,
					'sheets'       => $tab->name,
					'translations' => $meta->services[$service]->translations ?? [],
				])), $progress);
			}
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

	/**
	 * @param string $file
	 *
	 * @return MetaDto
	 *
	 * @throws ExcelException
	 * @throws MissingReflectionClassException
	 * @throws UnknownTypeException
	 * @throws ReflectionException
	 */
	protected function meta(string $file): MetaDto {
		/** @var $tabs TabDto[] */
		$tabs = [];
		$services = [];
		$total = 0;
		foreach ($this->safeRead($this->dtoService->fromArray(ReadDto::class, [
			'file'   => $file,
			'sheets' => 'tabs',
		])) as $tab) {
			$count = iterator_count($this->safeRead($this->dtoService->fromArray(ReadDto::class, [
				'file'   => $file,
				'sheets' => $tab['tab'],
			])));

			$services = array_merge($services, $handlers = array_map('trim', explode(',', $tab['services'])));
			$tabs[] = $this->dtoService->fromArray(TabDto::class, [
				'name'     => $tab['tab'],
				'services' => $handlers,
				'count'    => $count,
			]);
		}
		foreach ($tabs as $tab) {
			foreach ($tab->services as $_) {
				$total += iterator_count($this->safeRead($this->dtoService->fromArray(ReadDto::class, [
					'file'   => $file,
					'sheets' => $tab->name,
				])));
			}
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
			'total'        => $total,
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
				$reflection = $this->reflectionService->toClass($service);
				if (!$reflection->is(IReader::class)) {
					throw new ExcelException(sprintf('Service [%s] does not implement interface [%s].', $service, IReader::class));
				}
				$dto = null;
				if (($handler = ($reflection->methods['handle'] ?? null)) && $handler instanceof IRequestMethod && ($request = $handler->request()) instanceof ClassParameter) {
					/** @var $request ClassParameter */
					$dto = $request->class();
				}
				return $this->dtoService->fromArray(ServiceDto::class, [
					'name'         => $service,
					'dto'          => $dto,
					'translations' => $items,
				]);
			}, $services)),
		]);
	}
}
