<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

trait GenericGeneratorTrait {
	/** @var GenericGenerator */
	protected $genericGenerator;

	/**
	 * @Inject
	 *
	 * @param GenericGenerator $genericGenerator
	 */
	public function setGenericGenerator(GenericGenerator $genericGenerator): void {
		$this->genericGenerator = $genericGenerator;
	}
}
