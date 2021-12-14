<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type\Utils;

trait ClassTypeTrait {
	/** @var string */
	public $class;
	/** @var string */
	public $className;
	/** @var string */
	public $namespace;
	/**
	 * @var string
	 * @description virtual path where the class lives
	 */
	public $module;

	public function class(): string {
		return $this->class;
	}

	public function className(): string {
		return $this->className;
	}

	public function namespace(): string {
		return $this->namespace;
	}

	public function module(): string {
		return $this->module;
	}
}
