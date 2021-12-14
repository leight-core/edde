<?php
declare(strict_types=1);

namespace Edde\Sdk;

trait NameResolverTrait {
	/** @var NameResolver */
	protected $nameResolver;

	/**
	 * @Inject
	 *
	 * @param NameResolver $nameResolver
	 */
	public function setNameResolver(NameResolver $nameResolver): void {
		$this->nameResolver = $nameResolver;
	}
}
