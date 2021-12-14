<?php
declare(strict_types=1);

namespace Edde\Rest;

trait EndpointInfoTrait {
	/** @var IEndpointInfo */
	protected $endpointInfo;

	/**
	 * @Inject
	 *
	 * @param IEndpointInfo $endpointInfo
	 */
	public function setEndpointInfo(IEndpointInfo $endpointInfo): void {
		$this->endpointInfo = $endpointInfo;
	}
}
