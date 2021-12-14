<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

trait ClassGeneratorTrait {
	/** @var ClassGenerator */
	protected $classGenerator;

	/**
	 * @Inject
	 *
	 * @param ClassGenerator $classGenerator
	 */
	public function setClassGenerator(ClassGenerator $classGenerator): void {
		$this->classGenerator = $classGenerator;
	}
}
