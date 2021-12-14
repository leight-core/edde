<?php
declare(strict_types=1);

namespace Edde\Http;

trait LinkFilterTrait {
	/** @var ILinkFilter */
	protected $linkFilter;

	/**
	 * @Inject
	 *
	 * @param ILinkFilter $linkFilter
	 */
	public function setLinkFilter(ILinkFilter $linkFilter): void {
		$this->linkFilter = $linkFilter;
	}
}
