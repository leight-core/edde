<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto;

use Edde\Dto\AbstractDto;
use Edde\Reflection\Dto\Method\IRequestMethod;
use Edde\Reflection\Dto\Method\IResponseMethod;
use Edde\Reflection\Dto\Method\MethodDto;
use Edde\Reflection\Dto\Property\AbstractProperty;
use ReflectionClass;
use ReflectionException;
use function in_array;

class ClassDto extends AbstractDto {
	/**
	 * @var string
	 * @description class name
	 */
	public $name;
	/**
	 * @var string
	 */
	public $namespace;
	/**
	 * @var string
	 */
	public $module;
	/**
	 * @var string
	 * @description fully qualified class name
	 */
	public $fqdn;
	/**
	 * @var string[]
	 */
	public $annotations;
	/**
	 * @var bool
	 */
	public $hasTemplates;
	/**
	 * @var TemplateDto[]|null
	 */
	public $templates;
	/**
	 * @var string[]
	 */
	public $interfaces;
	/**
	 * @var AbstractProperty[]
	 * @description list of properties a class have
	 */
	public $properties;
	/**
	 * @var ConstantDto[]
	 * @description list of exported constants a class have
	 */
	public $constants;
	/**
	 * @var MethodDto[]
	 */
	public $methods;
	/**
	 * @var ReflectionClass
	 */
	protected $reflection;

	public function is(string $interface): bool {
		return in_array($interface, $this->interfaces ?? []);
	}

	/**
	 * @return ReflectionClass
	 *
	 * @throws ReflectionException
	 */
	public function reflection(): ReflectionClass {
		return $this->reflection ?? $this->reflection = new ReflectionClass($this->fqdn);
	}

	public function getRequestMethod(string $name): ?IRequestMethod {
		return ($method = $this->getMethod($name)) instanceof IRequestMethod ? $method : null;
	}

	public function getResponseMethod(string $name): ?IResponseMethod {
		return ($method = $this->getMethod($name)) instanceof IResponseMethod ? $method : null;
	}

	public function getMethod(string $name): ?MethodDto {
		return $this->methods[$name] ?? null;
	}

	public function getRequestClassOf(string $name): ?string {
		return ($method = $this->getRequestMethod($name)) ? (($request = $method->requestClass()) ? $request->class() : null) : null;
	}

	public function getResponseClassOf(string $name): ?string {
		return ($method = $this->getResponseMethod($name)) ? (($response = $method->response()) ? $response->class() : null) : null;
	}

	public function __toString() {
		return $this->fqdn;
	}
}
