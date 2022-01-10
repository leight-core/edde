<?php
declare(strict_types=1);

namespace Edde\Translation\Import;

use Edde\Cache\DatabaseCacheTrait;
use Edde\Dto\DtoServiceTrait;
use Edde\Excel\AbstractImport;
use Edde\Progress\Dto\ItemDto;
use Edde\Progress\IProgress;
use Edde\Translation\Dto\Ensure\EnsureDto;
use Edde\Translation\Dto\TranslationsDto;
use Edde\Translation\Repository\TranslationRepositoryTrait;

class TranslationImportService extends AbstractImport {
	use DatabaseCacheTrait;
	use TranslationRepositoryTrait;
	use DtoServiceTrait;

	public function import(string $file, $importDto = null, IProgress $progress = null) {
		parent::import($file, $importDto, $progress);
		$this->databaseCache->delete(TranslationsDto::class);
	}

	public function process(ItemDto $itemDto, IProgress $progress) {
		return $this->translationRepository->ensure($this->dtoService->fromArray(EnsureDto::class, [
			'translation' => $this->check($itemDto, [
				'language',
				'label',
				'translation',
			]),
		]));
	}
}
