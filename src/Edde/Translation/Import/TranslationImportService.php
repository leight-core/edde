<?php
declare(strict_types=1);

namespace Edde\Translation\Import;

use Edde\Cache\DatabaseCacheTrait;
use Edde\Reader\AbstractReader;
use Edde\Translation\Dto\Create\TranslationDto;
use Edde\Translation\Dto\Ensure\EnsureDto;
use Edde\Translation\Dto\TranslationsDto;
use Edde\Translation\Repository\TranslationRepositoryTrait;

class TranslationImportService extends AbstractReader {
	use DatabaseCacheTrait;
	use TranslationRepositoryTrait;

	/**
	 * @param TranslationDto $item
	 *
	 * @return TranslationDto
	 */
	public function handle($item) {
		return $this->translationRepository->ensure($this->dtoService->fromArray(EnsureDto::class, [
			'translation' => $item,
		]));
	}

	protected function onFinish() {
		parent::onFinish();
		$this->databaseCache->delete(TranslationsDto::class);
	}
}
