<?php
declare(strict_types=1);

namespace Edde\Translation\Import;

use Edde\Cache\CacheTrait;
use Edde\Import\AbstractImporter;
use Edde\Translation\Dto\Create\TranslationDto;
use Edde\Translation\Dto\Ensure\EnsureDto;
use Edde\Translation\Dto\TranslationsDto;
use Edde\Translation\Repository\TranslationRepositoryTrait;

class TranslationImportService extends AbstractImporter {
	use TranslationRepositoryTrait;
	use CacheTrait;

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
		$this->cache->delete(TranslationsDto::class);
	}
}
