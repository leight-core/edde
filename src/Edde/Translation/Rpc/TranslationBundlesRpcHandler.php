<?php
declare(strict_types=1);

namespace Edde\Translation\Rpc;

use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;
use Edde\Translation\Repository\TranslationRepositoryTrait;
use Edde\Translation\Schema\TranslationBundlesSchema;

class TranslationBundlesRpcHandler extends AbstractRpcHandler {
	use TranslationRepositoryTrait;

	protected $responseSchema = TranslationBundlesSchema::class;
	protected $requestSchemaOptional = true;

	public function handle(SmartDto $request) {
		$bundles = [];
		foreach ($this->translationRepository->languages() as ['locale' => $locale]) {
			$bundles[] = [
				'language'     => $locale,
				'translations' => $this->translationRepository->translationsOf($locale),
			];
		}
		$bundles = [
			'bundles' => $bundles,
		];
		return $this->toDto($bundles);
	}
}
