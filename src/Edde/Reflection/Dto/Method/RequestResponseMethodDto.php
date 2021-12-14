<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Method;

use Edde\Reflection\Dto\Type\AbstractType;

class RequestResponseMethodDto extends MethodDto implements IRequestResponseMethod {
	use RequestMethodTrait;

	/** @var AbstractType */
	public $response;

	public function response(): AbstractType {
		return $this->response;
	}
}
