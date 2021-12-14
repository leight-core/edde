<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Method;

use Edde\Reflection\Dto\Type\AbstractType;

/**
 * Method with just a return type (without any parameters).
 */
class ResponseMethodDto extends MethodDto implements IResponseMethod {
	/** @var AbstractType */
	public $response;

	public function response(): AbstractType {
		return $this->response;
	}
}
