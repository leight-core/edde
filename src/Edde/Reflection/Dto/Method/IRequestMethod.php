<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Method;

use Edde\Reflection\Dto\Parameter\AbstractParameter;
use Edde\Reflection\Dto\Parameter\ClassParameter;

interface IRequestMethod {
	public function request(): AbstractParameter;

	public function requestClass(): ?ClassParameter;

	/**
	 * Return request class name of the request parameter or throw an
	 * exception if input is not class (or generic).
	 *
	 * @return string
	 */
	public function toClass(): string;
}
