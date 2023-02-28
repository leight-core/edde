<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Endpoint;

use Doctrine\ORM\Query;
use Edde\Cache\CacheTrait;
use Edde\Doctrine\EntityManagerTrait;
use Edde\Dto\DtoServiceTrait;
use Edde\Rest\Endpoint\AbstractFetchEndpoint;
use Edde\Translation\Dto\TranslationsDto;
use Edde\Translation\Repository\TranslationRepositoryTrait;

/**
 * @description Get all the translations available in the application.
 */
class TranslationEndpoint extends AbstractFetchEndpoint {
	use EntityManagerTrait;
	use TranslationRepositoryTrait;
	use DtoServiceTrait;
	use CacheTrait;

	/**
	 * @return TranslationsDto
	 */
	public function get() {
		return $this->cache->get('translations', function (string $key) {
			$bundles = [];
			$languages = $this->entityManager->createQuery("
				SELECT DISTINCT t.locale FROM \Edde\Translation\Entity\TranslationEntity t
			");
			$languages->setHint(Query::HINT_READ_ONLY, true);
			foreach ($languages->toIterable() as ['locale' => $locale]) {
				$bundles[] = [
					'language'     => $locale,
					'translations' => $this->entityManager
						->createQuery('
							SELECT
								t.key,
								t.translation as value 
							FROM
								\Edde\Translation\Entity\TranslationEntity t
							WHERE
								t.locale = :locale
						')
						->setHint(Query::HINT_READ_ONLY, true)
						->setParameter('locale', $locale)
						->getArrayResult(),
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
