<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Method;

class RequestMethodDto extends MethodDto implements IRequestMethod {
	use RequestMethodTrait;
}
