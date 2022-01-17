<?php
declare(strict_types=1);

namespace Edde\Translation\Api;

use Edde\Cache\DatabaseCacheTrait;
use Edde\Dto\DtoServiceTrait;
use Edde\Rest\Endpoint\AbstractFetchEndpoint;
use Edde\Translation\Dto\TranslationsDto;
use Edde\Translation\Mapper\ToTranslationMapperTrait;
use Edde\Translation\Repository\TranslationRepositoryTrait;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * @description Get all the translations available in the application.
 */
class TranslationEndpoint extends AbstractFetchEndpoint {
	use ToTranslationMapperTrait;
	use TranslationRepositoryTrait;
	use DatabaseCacheTrait;
	use DtoServiceTrait;

	/**
	 * @return TranslationsDto
	 *
	 * @throws InvalidArgumentException
	 */
	public function get(): TranslationsDto {
		return $this->databaseCache->get(TranslationsDto::class, function (string $key) {
			$this->databaseCache->set($key, $value = $this->dtoService->fromArray(TranslationsDto::class, ['translations' => $this->toTranslationMapper->map($this->translationRepository->all())]));
			return $value;
		});
	}
}
