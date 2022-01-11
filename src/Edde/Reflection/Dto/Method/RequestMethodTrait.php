<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Method;

use Edde\Reflection\Dto\Parameter\AbstractParameter;
use Edde\Reflection\Dto\Parameter\ClassParameter;
use Edde\Reflection\Dto\Type\Utils\IClassType;
use Edde\Reflection\Dto\Type\Utils\IGenericType;
use Edde\Rest\Exception\RestException;

trait RequestMethodTrait {
	/** @var AbstractParameter */
	public $request;

	public function request(): AbstractParameter {
		return $this->request;
	}

	public function requestClass(): ?ClassParameter {
		return $this->request instanceof ClassParameter ? $this->request : null;
	}

	public function toClass(): string {
		$request = $this->request();
		if ($request instanceof IClassType) {
			return $request->class();
		} else if ($request instanceof IGenericType) {
			return $request->type()->class;
		}
		throw new RestException("Wrong request method type; just ClassType or GenericType are supported.");
	}
}
