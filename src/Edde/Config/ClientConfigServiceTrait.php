<?php
declare(strict_types=1);

namespace Edde\Config;

trait ClientConfigServiceTrait {
	/** @var ClientConfigService */
	protected $clientConfigService;

	/**
	 * @Inject
	 *
	 * @param ClientConfigService $clientConfigService
	 */
	public function setClientConfigService(ClientConfigService $clientConfigService): void {
		$this->clientConfigService = $clientConfigService;
	}
}
