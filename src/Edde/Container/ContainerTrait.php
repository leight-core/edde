<?php
declare(strict_types=1);

namespace Edde\Container;

use DI\Container;

trait ContainerTrait {
	/** @var Container */
	protected $container;

	/**
	 * @Inject
	 *
	 * @param Container $container
	 */
	public function setContainer(Container $container): void {
		$this->container = $container;
	}
}
