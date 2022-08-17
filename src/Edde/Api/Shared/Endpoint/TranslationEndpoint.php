<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Endpoint;

use Edde\Cache\CacheTrait;
use Edde\Dto\DtoServiceTrait;
use Edde\Rest\Endpoint\AbstractFetchEndpoint;
use Edde\Translation\Dto\TranslationsDto;
use Edde\Translation\Mapper\SimpleTranslationMapperTrait;
use Edde\Translation\Repository\TranslationRepositoryTrait;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * @description Get all the translations available in the application.
 */
class TranslationEndpoint extends AbstractFetchEndpoint {
	use SimpleTranslationMapperTrait;
	use TranslationRepositoryTrait;
	use DtoServiceTrait;
	use CacheTrait;

	/**
	 * @return TranslationsDto
	 *
	 * @throws InvalidArgumentException
	 */
	public function get() {
		return $this->cache->get('translations', function (string $key) {
			$bundles = [];
			foreach ($this->translationRepository->toLanguages() as $language) {
				$bundles[] = [
					'language'     => $language->locale,
					'translations' => $this->simpleTranslationMapper->map($this->translationRepository->fetchByLocale($language->locale)),
				];
			}
			$bundles = [
				'bundles' => $bundles,
			];
			$this->cache->set($key, $bundles);
			return $bundles;
		});
	}
}
