<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Method;

use Edde\Reflection\Dto\Parameter\AbstractParameter;

/**
 * Method with more than 1 argument without response.
 */
class ParameterMethodDto extends MethodDto {
	/**
	 * @var AbstractParameter
	 */
	public $request;
	/**
	 * @var AbstractParameter[]
	 */
	public $parameters;
}
