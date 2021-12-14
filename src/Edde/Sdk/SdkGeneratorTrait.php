<?php
declare(strict_types=1);

namespace Edde\Sdk;

trait SdkGeneratorTrait {
	/** @var SdkGenerator */
	protected $sdkGenerator;

	/**
	 * @Inject
	 *
	 * @param SdkGenerator $sdkGenerator
	 */
	public function setSdkGenerator(SdkGenerator $sdkGenerator): void {
		$this->sdkGenerator = $sdkGenerator;
	}
}
