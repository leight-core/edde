<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Container\ContainerTrait;
use Edde\Dto\DtoServiceTrait;
use Edde\Excel\Exception\EmptySheetException;
use Edde\Excel\Exception\ExcelException;
use Edde\Excel\Exception\MissingHeaderException;
use Edde\Import\AbstractImportService;
use Edde\Job\Exception\JobInterruptedException;
use Edde\Mapper\Exception\SkipException;
use Edde\Php\Exception\MemoryLimitException;
use Edde\Progress\Dto\ItemDto;
use Edde\Progress\IProgress;
use Edde\Progress\NoProgress;
use Edde\Translation\Mapper\TranslatingMapperTrait;
use Edde\Translation\TranslationServiceTrait;
use PhpOffice\PhpSpreadsheet\Exception;
use Throwable;
use function get_class;
use function iterator_count;
use function sprintf;

abstract class AbstractImport extends AbstractImportService implements IExcelImport {
	use ExcelServiceTrait;
	use ContainerTrait;
	use TranslationServiceTrait;
	use TranslatingMapperTrait;
	use DtoServiceTrait;

	public function import(string $file, $params = null, IProgress $progress = null) {
		$progress = NoProgress::ensure($progress);
		$progress->check();
		$count = 0;
		foreach ($sheets = ($params['sheets'] ?? [null => $this]) as $sheet => $service) {
			try {
				$count += iterator_count($this->excelService->read($file, 0, 1, $this->translateSheet($sheet)));
				/**
				 * Counting can take some time, so let user choose to kill the job.
				 */
				$progress->check();
			} catch (EmptySheetException $exception) {
				// this is ok, swallow
			}
		}
		$progress->onStart($count);
		$progress->check();
		foreach ($sheets as $sheet => $service) {
			$sheet = $this->translateSheet($sheet, $progress);
			/** @var $handler IExcelImport */
			$handler = null;
			if ($service instanceof IExcelImport) {
				$handler = $service;
			} else if (!(($handler = $this->container->get($service)) instanceof IExcelImport)) {
				throw new ExcelException(sprintf('Import service [%s (%s)] is not instance of [%s].', $service, get_class($handler), IExcelImport::class));
			}
			$handler->sheet($file, $sheet, $progress);
		}
	}

	/**
	 * @param string         $file
	 * @param string|null    $sheet
	 * @param IProgress|null $progress
	 *
	 * @throws EmptySheetException
	 * @throws Exception
	 * @throws JobInterruptedException
	 * @throws MissingHeaderException
	 * @throws \Dibi\Exception
	 */
	public function sheet(string $file, string $sheet = null, IProgress $progress = null) {
		$progress = NoProgress::ensure($progress);
		$progress->check();
		foreach ($this->excelService->read($file, 0, 1, $sheet) as $index => $source) {
			$item = ItemDto::create([
				'index'  => $index,
				'source' => $source,
				'item'   => $this->translatingMapper->item($source, ['service' => static::class]),
			]);
			try {
				$progress->check();
				$this->process($item, $progress);
				$progress->onProgress($item);
			} catch (SkipException $exception) {
				$progress->log(
					$progress::LOG_WARNING,
					$exception->getMessage(),
					$item
				);
				$progress->onProgress($item);
			} catch (JobInterruptedException $exception) {
				/**
				 * When interrupted, re-throw the exception.
				 */
				throw $exception;
			} catch (MemoryLimitException $exception) {
				$progress->onError($exception, $item);
				throw new JobInterruptedException($exception->getMessage(), 0, $exception);
			} catch (Throwable $throwable) {
				$progress->onError($throwable, $item);
			}
		}
	}

	protected function translateSheet(?string $sheet, ?IProgress $progress = null): ?string {
		if (!$sheet) {
			return null;
		}
		$translation = $this->translationService->translation($key = static::class . '.sheet.' . $sheet, $sheet);
		$progress && $progress->log(IProgress::LOG_INFO, sprintf('Translating sheet [%s] with a key [%s] to [%s].', $sheet, $key, $translation));
		return $translation;
	}

	abstract public function process(ItemDto $itemDto, IProgress $progress);
}
