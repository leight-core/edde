<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Method;

use Edde\Reflection\Dto\Parameter\AbstractParameter;

interface IRequestMethod {
	public function request(): AbstractParameter;

	/**
	 * Return request class name of the request parameter or throw an
	 * exception if input is not class (or generic).
	 *
	 * @return string
	 */
	public function toClass(): string;
}
