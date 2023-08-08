<?php
declare(strict_types=1);

namespace Edde\Translation\Rpc;

use Doctrine\ORM\Query;
use Edde\Cache\CacheTrait;
use Edde\Doctrine\EntityManagerTrait;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;
use Edde\Translation\Repository\TranslationRepositoryTrait;
use Edde\Translation\Schema\TranslationBundlesSchema;

class TranslationBundlesRpcHandler extends AbstractRpcHandler {
	use EntityManagerTrait;
	use TranslationRepositoryTrait;
	use CacheTrait;

	protected $responseSchema = TranslationBundlesSchema::class;
	protected $requestSchemaOptional = true;

	public function handle(SmartDto $request) {
		return $this->cache->get('translations', function (string $key) {
			$bundles = [];
			$languages = $this->entityManager
				->createQuery("
					SELECT DISTINCT t.locale FROM \Edde\Translation\Entity\TranslationEntity t
				")
				->enableResultCache();
			$languages->setHint(Query::HINT_READ_ONLY, true);
			foreach ($languages->toIterable() as ['locale' => $locale]) {
				$bundles[] = (object)[
					'language'     => $locale,
					'translations' => array_map(function ($item) {
						return (object)$item;
					}, $this->entityManager
						->createQuery('
							SELECT
								t.key,
								t.translation as value 
							FROM
								\Edde\Translation\Entity\TranslationEntity t
							WHERE
								t.locale = :locale
						')
						->enableResultCache()
						->setHint(Query::HINT_READ_ONLY, true)
						->setParameter('locale', $locale)
						->getArrayResult()),
				];
			}
			$bundles = [
				'bundles' => $bundles,
			];
			return $this->cache->set($key, $this->toDto($bundles));
		});
	}
}
