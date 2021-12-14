<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Excel\Exception\ExcelException;
use Edde\Progress\Dto\ItemDto;
use Edde\Progress\IProgress;

abstract class AbstractSheetImport extends AbstractImport {
	public function import(string $file, $params = null, IProgress $progress = null) {
		parent::import($file, ['sheets' => $this->getSheets()], $progress);
	}

	abstract public function getSheets(): array;

	public function process(ItemDto $itemDto, IProgress $progress) {
		throw new ExcelException(sprintf('Import [%s] does not support processing individual items.', static::class));
	}
}
