<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

trait PropertyGeneratorTrait {
	/** @var PropertyGenerator */
	protected $propertyGenerator;

	/**
	 * @Inject
	 *
	 * @param PropertyGenerator $propertyGenerator
	 */
	public function setPropertyGenerator(PropertyGenerator $propertyGenerator): void {
		$this->propertyGenerator = $propertyGenerator;
	}
}
