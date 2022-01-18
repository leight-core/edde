<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Endpoint;

use Edde\Cache\CacheTrait;
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
	use DtoServiceTrait;
	use CacheTrait;

	/**
	 * @return TranslationsDto
	 *
	 * @throws InvalidArgumentException
	 */
	public function get(): TranslationsDto {
		return $this->cache->get(TranslationsDto::class, function (string $key) {
			$this->cache->set($key, $value = $this->dtoService->fromArray(TranslationsDto::class, ['translations' => $this->toTranslationMapper->map($this->translationRepository->all())]));
			return $value;
		});
	}
}
