<?php
declare(strict_types=1);

namespace Edde\Http;

trait HttpIndexTrait {
	/** @var IHttpIndex */
	protected $httpIndex;

	/**
	 * @Inject
	 *
	 * @param IHttpIndex $httpIndex
	 */
	public function setHttpIndex(IHttpIndex $httpIndex): void {
		$this->httpIndex = $httpIndex;
	}
}
