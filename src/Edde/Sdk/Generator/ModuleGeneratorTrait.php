<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

trait ModuleGeneratorTrait {
	/** @var ModuleGenerator */
	protected $moduleGenerator;

	/**
	 * @Inject
	 *
	 * @param ModuleGenerator $moduleGenerator
	 */
	public function setModuleGenerator(ModuleGenerator $moduleGenerator): void {
		$this->moduleGenerator = $moduleGenerator;
	}
}
