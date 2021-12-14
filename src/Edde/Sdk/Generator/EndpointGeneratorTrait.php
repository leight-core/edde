<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

trait EndpointGeneratorTrait {
	/** @var EndpointGenerator */
	protected $endpointGenerator;

	/**
	 * @Inject
	 *
	 * @param EndpointGenerator $endpointGenerator
	 */
	public function setEndpointGenerator(EndpointGenerator $endpointGenerator): void {
		$this->endpointGenerator = $endpointGenerator;
	}
}
