<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

trait FormGeneratorTrait {
	/** @var FormGenerator */
	protected $formGenerator;

	/**
	 * @Inject
	 *
	 * @param FormGenerator $formGenerator
	 */
	public function setFormGenerator(FormGenerator $formGenerator): void {
		$this->formGenerator = $formGenerator;
	}
}
