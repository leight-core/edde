<?php
declare(strict_types=1);

namespace Edde\Link;

use DI\Annotation\Inject;

trait LinkGeneratorTrait {
	/** @var LinkGenerator */
	protected $linkGenerator;

	/**
	 * @Inject
	 *
	 * @param LinkGenerator $linkGenerator
	 */
	public function setLinkGenerator(LinkGenerator $linkGenerator): void {
		$this->linkGenerator = $linkGenerator;
	}
}
