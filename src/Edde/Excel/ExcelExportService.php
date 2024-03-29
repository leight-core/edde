<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Config\ConfigServiceTrait;
use Edde\Dto\DtoServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Excel\Dto\Export\ExcelExportDto;
use Edde\Excel\Dto\Export\MetaDto;
use Edde\Excel\Dto\ReadDto;
use Edde\File\FileServiceTrait;
use Edde\File\Repository\FileRepositoryTrait;
use Edde\Log\LoggerTrait;
use Edde\Php\MemoryServiceTrait;
use Edde\Progress\IProgress;
use Edde\Progress\NoProgress;
use Edde\Source\SourceServiceTrait;
use Edde\Stream\FileStream;
use Edde\User\CurrentUserServiceTrait;
use League\Uri\Uri;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Settings;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Throwable;
use function array_map;
use function array_values;
use function chr;
use function date;
use function json_encode;
use function ord;
use function reset;
use function sprintf;

class ExcelExportService implements IExcelExportService {
	use ExcelServiceTrait;
	use DtoServiceTrait;
	use SourceServiceTrait;
	use FileServiceTrait;
	use CurrentUserServiceTrait;
	use MemoryServiceTrait;
	use ConfigServiceTrait;
	use FileRepositoryTrait;
	use LoggerTrait;

	const CONFIG_USE_CACHE = 'export.cache';

	public function meta(string $file): MetaDto {
		$tabs = [];
		$export = $this->excelService->safeRead($this->dtoService->fromArray(ReadDto::class, [
			'file'   => $file,
			'sheets' => 'export',
		]));
		foreach ($export as $item) {
			$tabs[$item['tab']] = $tabs[$item['tab']] ?? ['name' => $item['tab']];
			$tabs[$item['tab']]['sources']['sources'][] = [
				'name'   => $item['name'],
				'source' => $item['source'],
				'mapper' => $item['mapper'],
			];
		}
		$groups = [];
		$groupIndex = 0;
		$groupExport = [];
		/**
		 * This whole beast is here to extract metadata about exported cells; they're grouped to optimize
		 * one time query and that's the reason for this magic.
		 *
		 * So, go through all tabs (being marked for export)...
		 */
		foreach ($tabs as $name => $_) {
			$exports = [];
			$sheet = $this->excelService->iterate($this->dtoService->fromArray(ReadDto::class, [
				'file'   => $file,
				'sheets' => $name,
			]));
			/**
			 * Then find all cells in the sheet - this expects a sheet is empty, so this should **not** take
			 * huge amount of time; quite big fun could be to run this on some big file.
			 *
			 * The goal here is to find cell marked for export, get their export string and cell address.
			 */
			/** @var $cell Cell */
			foreach ($sheet as $address => [$cell, $value]) {
				[
					$x,
					$y,
				] = $address;

				try {
					/**
					 * This hack is quite epic - if the cell has hyperlink, try to parse it and use it as
					 * path for an export. Everything else is ignored.
					 */
					if (($url = Uri::createFromString($cell->getHyperlink()->getUrl()))->getScheme() !== 'export') {
						continue;
					}
					$exports[$y][$x] = $url;
				} catch (Throwable $throwable) {
					/**
					 * If a link is malformed, we do not care.
					 */
					continue;
				}
			}
			/**
			 * So no we have all cells in the sheet being exported, with an address and export string.
			 *
			 * Now it's time to do another hack - group cells by a row; this is an optimization which enables
			 * engine providing source data to do just one query per a group instead of query per cell.
			 */
			foreach ($exports as $y => $xs) {
				$lastX = null;
				$lastY = null;
				/**
				 * Group cells together - just when consecutivness is broken, new group is introduced, that means
				 * - change column in the same row
				 * - change row in the same column
				 * - same row, but skipped column
				 */
				foreach ($xs as $x => $xs) {
					/**
					 * Create a new group when there is a missing piece (so the row is not consecutive)
					 */
					(chr(ord($x) - 1) !== $lastX) && $groupIndex++;
					/**
					 * Also create a new group when a column is the same, but the row changes
					 */
					((chr(ord($x) - 1) === $lastX) && ($y !== $lastY)) && $groupIndex++;
					$groups[$name][$groupIndex][] = [
						$x,
						$y,
						$xs,
					];
					$lastX = $x;
					$lastY = $y;
				}
			}
			/**
			 * This part here is just to regroup all data to be usable for the end user - group groups into groups,
			 * put cells inside and go on.
			 *
			 * This is basically mapper for the end DTO used in MetaDto class.
			 */
			foreach ($groups as $name => $cellGroups) {
				foreach ($cellGroups as $cellGroup) {
					$groupExport[$name] = $groupExport[$name] ?? [
						'name'   => $name,
						'groups' => [],
					];

					$first = reset($cellGroup);
					$groupExport[$name]['groups'][] = [
						'first'   => [
							$first[0],
							$first[1],
						],
						'queries' => array_map(function (array $cell) {
							return (string)$cell[2];
						}, $cellGroup),
						'cells'   => array_map(function (array $cell) {
							return [
								'x'      => $cell[0],
								'y'      => $cell[1],
								'source' => (string)$cell[2],
							];
						}, $cellGroup),
					];
				}
			}
		}

		foreach ($tabs as $name => $tab) {
			$tabs[$name]['groups'] = $groupExport[$name];
		}

		return $this->dtoService->fromArray(MetaDto::class, [
			'tabs' => array_values($tabs),
		]);
	}

	public function export(ExcelExportDto $excelExportDto, IProgress $progress = null): SmartDto {
		$progress = NoProgress::ensure($progress);
		$progress->log(IProgress::LOG_INFO, 'Starting Export Service.');
		$this->logger->debug('Starting Export Service.', ['tags' => ['export']]);
		$progress->onStart(8);
		$template = ($templateFile = $this->fileRepository->find($excelExportDto->templateId))->native;
		$progress->log(IProgress::LOG_INFO, sprintf('Resolved export template [%s].', $template));
		$this->logger->debug(sprintf('Resolved export template [%s].', $template), ['tags' => ['export']]);
		$progress->onProgress();
		$target = date('Y-m-d H-i-s') . ' ' . $templateFile->name;
		$meta = $this->meta($template);
		$progress->log(IProgress::LOG_INFO, 'Resolved template meta data.');
		$progress->log(IProgress::LOG_INFO, json_encode($meta));
		$this->logger->debug('Resolved template meta data.', ['tags' => ['export']]);
		$this->logger->debug(json_encode($meta), [
			'tags' => ['export'],
			'meta' => $meta,
		]);
		$progress->onProgress();
		$file = $this->fileService->store(FileStream::openRead($template), '/export/excel', $target, null, $this->currentUserService->requiredId());
		$progress->log(IProgress::LOG_INFO, sprintf('Storing to [%s].', $file->native));
		$this->logger->debug(sprintf('Storing to [%s].', $file->native), ['tags' => ['export']]);
		$progress->onProgress();
		if ($this->configService->system(self::CONFIG_USE_CACHE, true)) {
			Settings::setCache(new Psr16Cache(new FilesystemAdapter()));
		}
		$this->memoryService->log();
		$spreadsheet = $this->excelService->load($this->dtoService->fromArray(ReadDto::class, [
			'file' => $template,
		]));
		$progress->log(IProgress::LOG_INFO, 'Read spreadsheets.');
		$this->logger->debug('Read spreadsheets.', ['tags' => ['export']]);
		$progress->onProgress();
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$progress->onProgress();
		$progress->log(IProgress::LOG_INFO, 'Reading tabs.');
		$this->logger->debug('Reading tabs.', ['tags' => ['export']]);
		foreach ($meta->tabs as $tab) {
			$progress->log(IProgress::LOG_INFO, sprintf('Reading tab [%s].', $tab->name));
			$this->logger->debug(sprintf('Reading tab [%s], [%s].', $tab->name, json_encode($tab)), ['tags' => ['export']]);
			$source = $this->sourceService->source($tab->sources, $excelExportDto->queries);
			$worksheet = $spreadsheet->getSheetByName($tab->name);
			foreach ($tab->groups->groups as $group) {
				$progress->log(IProgress::LOG_INFO, 'Querying data from the group.');
				$this->logger->debug(sprintf('Querying data from the group [%s].', json_encode($group)), ['tags' => ['export']]);
				try {
					/**
					 * Make a query
					 */
					$row = 0;
					foreach ($source->group($group->queries) as $values) {
						/**
						 * Run through all returned values of the query and fill cells
						 */
						foreach ($values as $index => $value) {
							$this->memoryService->check(80);
							$cell = $group->cells[$index];
							$worksheetCell = $worksheet->getCell($cell->x . ($cell->y + $row))->setValue($value);
							$worksheetCell->setHyperlink();
						}
						$row++;
					}
				} catch (Throwable $exception) {
					$progress->log(IProgress::LOG_ERROR, sprintf('Cannot query data from the group: %s', $exception->getMessage()));
					$this->logger->error($exception, ['tags' => ['export']]);
				}
			}
		}
		$progress->log(IProgress::LOG_INFO, 'All exported.');
		$this->logger->debug('All exported.', ['tags' => ['export']]);
		$progress->onProgress();
		$writer->save($file->native);
		$progress->log(IProgress::LOG_INFO, 'Save done.');
		$this->logger->debug('Save done.', ['tags' => ['export']]);
		$progress->onProgress();
		$this->fileService->refresh($file->id);
		$progress->onProgress();
		$this->memoryService->log();
		$progress->log(IProgress::LOG_INFO, 'Finished.');
		$this->logger->debug('Finished.', ['tags' => ['export']]);
		return $file;
	}
}
