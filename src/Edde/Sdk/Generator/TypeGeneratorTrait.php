<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

trait TypeGeneratorTrait {
	/** @var TypeGenerator */
	protected $typeGenerator;

	/**
	 * @Inject
	 *
	 * @param TypeGenerator $typeGenerator
	 */
	public function setTypeGenerator(TypeGenerator $typeGenerator): void {
		$this->typeGenerator = $typeGenerator;
	}
}
