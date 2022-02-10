<?php
declare(strict_types=1);

namespace Edde\Reflection;

use Edde\Reflection\Dto\ClassDto;
use Edde\Reflection\Dto\ConstantDto;
use Edde\Reflection\Dto\Method\MethodDto;
use Edde\Reflection\Dto\Method\ParameterMethodDto;
use Edde\Reflection\Dto\Method\ParameterResponseMethodDto;
use Edde\Reflection\Dto\Method\RequestMethodDto;
use Edde\Reflection\Dto\Method\RequestResponseMethodDto;
use Edde\Reflection\Dto\Method\ResponseMethodDto;
use Edde\Reflection\Dto\Method\SimpleMethodDto;
use Edde\Reflection\Dto\Parameter\AbstractParameter;
use Edde\Reflection\Dto\Parameter\ClassParameter;
use Edde\Reflection\Dto\Parameter\GenericParameter;
use Edde\Reflection\Dto\Parameter\InterfaceParameter;
use Edde\Reflection\Dto\Parameter\MixedParameter;
use Edde\Reflection\Dto\Parameter\ScalarParameter;
use Edde\Reflection\Dto\Parameter\TemplateParameter;
use Edde\Reflection\Dto\Parameter\UnknownParameter;
use Edde\Reflection\Dto\Property\AbstractProperty;
use Edde\Reflection\Dto\Property\ClassProperty;
use Edde\Reflection\Dto\Property\GenericProperty;
use Edde\Reflection\Dto\Property\InterfaceProperty;
use Edde\Reflection\Dto\Property\MixedProperty;
use Edde\Reflection\Dto\Property\ScalarProperty;
use Edde\Reflection\Dto\Property\TemplateProperty;
use Edde\Reflection\Dto\Property\UnknownProperty;
use Edde\Reflection\Dto\TemplateDto;
use Edde\Reflection\Dto\Type\AbstractType;
use Edde\Reflection\Dto\Type\ClassType;
use Edde\Reflection\Dto\Type\GenericType;
use Edde\Reflection\Dto\Type\InterfaceType;
use Edde\Reflection\Dto\Type\MixedType;
use Edde\Reflection\Dto\Type\ScalarType;
use Edde\Reflection\Dto\Type\TemplateType;
use Edde\Reflection\Dto\Type\UnknownType;
use Edde\Reflection\Exception\MissingReflectionClassException;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Utils\StringUtils;
use Minime\Annotations\Reader;
use Nette\Utils\Reflection;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use Reflector;

class ReflectionService {
	/** @var Reader */
	protected $reader;

	public function __construct() {
		$this->reader = Reader::createFromDefaults();
	}

	/**
	 * @param string $class
	 *
	 * @return ClassDto
	 *
	 * @throws MissingReflectionClassException
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 */
	public function toClass(string $class): ClassDto {
		$class = new ReflectionClass($class);
		return ClassDto::create([
			'name'         => $class->getShortName(),
			'namespace'    => $class->getNamespaceName(),
			'module'       => $this->toModule($class->getNamespaceName()),
			'fqdn'         => $class->getName(),
			'annotations'  => $this->toAnnotations($class),
			'interfaces'   => array_map(function (ReflectionClass $class) {
				return $class->getName();
			}, $class->getInterfaces()),
			'templates'    => $templates = $this->toTemplates($class),
			'hasTemplates' => !empty($templates),
			'methods'      => $this->toMethods($class),
			'properties'   => $this->toProperties($class),
			'constants'    => $this->toConstants($class),
		]);
	}

	public function toTemplate(string $source): TemplateDto {
		$template = explode('=', $source);
		return TemplateDto::create([
			'name'    => $template[0],
			'default' => $template[1] ?? null,
		]);
	}

	/**
	 * @param ReflectionClass $class
	 *
	 * @return TemplateDto[]
	 */
	public function toTemplates(ReflectionClass $class): array {
		return array_map(function (string $template) {
			return $this->toTemplate($template);
		}, (array)$this->toAnnotation($class, 'template'));
	}

	/**
	 * @param ReflectionClass $class
	 *
	 * @return AbstractProperty[]
	 *
	 * @throws MissingReflectionClassException
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 */
	public function toProperties(ReflectionClass $class): array {
		return array_combine(array_map(function (ReflectionProperty $property) {
			return $property->getName();
		}, $class->getProperties(ReflectionProperty::IS_PUBLIC)), array_map(function (ReflectionProperty $property) {
			return $this->toProperty($property);
		}, $class->getProperties(ReflectionProperty::IS_PUBLIC)));
	}

	/**
	 * @param ReflectionClass $class
	 *
	 * @return ConstantDto[]
	 */
	public function toConstants(ReflectionClass $class): array {
		$constants = [];
		foreach ($class->getConstants() as $name => $value) {
			$constants[] = ConstantDto::create([
				'name'  => $name,
				'value' => $value,
			]);
		}
		return $constants;
	}

	/**
	 * @param string|null          $type
	 * @param ReflectionClass|null $class
	 * @param array                $extra
	 *
	 * @return array
	 *
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 * @throws MissingReflectionClassException
	 */
	public function parse(?string $type, ?ReflectionClass $class, array $extra = []): array {
		if (!$type) {
			return array_merge([
				'__type' => 'mixed',
			], $extra);
		}
		$types = array_map('trim', explode('|', $type));
		$dto = [
			'isRequired' => !in_array('null', array_map('strtolower', $types), true),
			'isOptional' => in_array('void', array_map('strtolower', $types), true),
			'isArray'    => strpos($type = reset($types), '[]') !== false,
		];
		$type = rtrim($type, '[]');
		if (class_exists($type) && !$class) {
			$class = new ReflectionClass($type);
		}
		if (!$class) {
			throw new MissingReflectionClassException(sprintf('Type [%s] is not a class, thus it needs a reflection class specified.', $type));
		}
		if (($generic1 = strpos($type, '<')) !== false && ($generic2 = strpos($type, '>')) !== false) {
			return array_merge($dto, [
				'__type'   => 'generic',
				'type'     => $this->toTypeString(substr($type, 0, $generic1), $class),
				'generics' => array_map(function (string $generic) use ($class) {
					return $this->toTypeString($generic, $class);
				}, array_map('trim', explode(',', substr($type, $generic1 + 1, $generic2 - $generic1 - 1)))),
			], $extra);
		}
		switch ($type) {
			/** @noinspection PhpMissingBreakStatementInspection */
			case 'array':
				$type = 'mixed';
				$dto['isArray'] = true;
			case 'string':
			case 'int':
			case 'float':
			case 'double':
			case 'bool':
			case 'void':
			case 'mixed':
				return array_merge($dto, [
					'__type' => 'scalar',
					'type'   => $type,
				], $extra);
		}

		if (class_exists($type) || class_exists($typeWithUse = $this->toUse($class, $type))) {
			try {
				$class = new ReflectionClass($typeWithUse ?? $type);
			} catch (ReflectionException $_) {
				$class = new ReflectionClass($type);
			}
			return array_merge($dto, [
				'__type'    => 'class',
				'class'     => $class->getName(),
				'className' => $class->getShortName(),
				'namespace' => $class->getNamespaceName(),
				'module'    => $this->toModule($class->getNamespaceName()),
			], $extra);
		}
		if (interface_exists($type) || interface_exists($typeWithUse ?? $type)) {
			try {
				$class = new ReflectionClass($typeWithUse ?? $type);
			} catch (ReflectionException $_) {
				$class = new ReflectionClass($type);
			}
			return array_merge($dto, [
				'__type'    => 'interface',
				'class'     => $class->getName(),
				'className' => $class->getShortName(),
				'namespace' => $class->getNamespaceName(),
				'module'    => $this->toModule($class->getNamespaceName()),
			], $extra);
		}

		if (($templates = $this->toTemplates($class)) && !empty($templates)) {
			$match = array_filter($templates, function (TemplateDto $templateDto) use ($type) {
				return $type === $templateDto->name;
			});
			if (!empty($match = reset($match))) {
				return array_merge($dto, [
					'__type'   => 'template',
					'template' => $match,
				], $extra);
			}
		}

		return [
			'__type' => 'unknown',
			'type'   => $type,
		];
	}

	/**
	 * @param ReflectionNamedType  $type
	 * @param ReflectionClass|null $class
	 *
	 * @return AbstractType
	 *
	 * @throws MissingReflectionClassException
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 */
	public function toType(ReflectionNamedType $type, ?ReflectionClass $class = null): AbstractType {
		return $this->toTypeString($type->getName(), $class, ['isRequired' => !$type->allowsNull()]);
	}

	/**
	 * @param string|null          $type
	 * @param ReflectionClass|null $class
	 * @param array                $extra
	 *
	 * @return AbstractType
	 *
	 * @throws MissingReflectionClassException
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 */
	public function toTypeString(?string $type, ?ReflectionClass $class = null, array $extra = []): AbstractType {
		$dto = $this->parse($type, $class, array_merge(['isInternal' => false], $extra));
		switch ($dto['__type']) {
			case 'scalar':
				return ScalarType::create($dto);
			case 'class':
				return ClassType::create($dto);
			case 'interface':
				return InterfaceType::create($dto);
			case 'template':
				return TemplateType::create($dto);
			case 'generic':
				return GenericType::create($dto);
			case 'mixed':
				return MixedType::create($dto);
			case 'unknown':
				return UnknownType::create($dto);
		}
		return MixedType::create($dto);
	}

	/**
	 * @param ReflectionParameter $parameter
	 *
	 * @return AbstractParameter
	 *
	 * @throws MissingReflectionClassException
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 */
	public function toParameter(ReflectionParameter $parameter): AbstractParameter {
		$dto = [
			'name'       => $parameter->getName(),
			'isVariadic' => $parameter->isVariadic(),
			'isRequired' => $parameter->isOptional(),
			'isInternal' => false,
		];
		$class = $parameter->getDeclaringClass();
		$hasParam = false;
		foreach ((array)$this->toAnnotation($parameter->getDeclaringFunction(), 'param') as $param) {
			if ($hasParam = (StringUtils::match($param, "~\\$(?<name>[a-zA-Z0-9_]+)~", true)['name'] === $parameter->getName())) {
				$dto = array_merge($dto, $this->parse(trim(str_replace('$' . $parameter->getName(), '', $param)), $class));
				break;
			}
		}
		if (!$hasParam) {
			$dto = array_merge($dto, $this->parse($parameter->hasType() ? $parameter->getType()->getName() : null, $class));
		}
		switch ($dto['__type'] ?? null) {
			case 'scalar':
				return ScalarParameter::create($dto);
			case 'class':
				return ClassParameter::create($dto);
			case 'interface':
				return InterfaceParameter::create($dto);
			case 'template':
				return TemplateParameter::create($dto);
			case 'generic':
				return GenericParameter::create($dto);
			case 'mixed':
				return MixedParameter::create($dto);
			case 'unknown':
				return UnknownParameter::create($dto);
		}

		return MixedParameter::create($dto);
	}

	/**
	 * @param ReflectionMethod $method
	 *
	 * @return AbstractParameter[]
	 *
	 * @throws MissingReflectionClassException
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 */
	public function toParameters(ReflectionMethod $method): array {
		return array_map(function (ReflectionParameter $parameter) {
			return $this->toParameter($parameter);
		}, $method->getParameters());
	}

	/**
	 * @param ReflectionProperty $property
	 *
	 * @return AbstractType
	 *
	 * @throws MissingReflectionClassException
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 */
	public function toProperty(ReflectionProperty $property): AbstractType {
		$class = $property->getDeclaringClass();
		$defaults = $class->getDefaultProperties();
		$dto = [
			'name'       => $property->getName(),
			'isInternal' => $this->toAnnotation($property, 'internal') !== null,
			'value'      => $defaults[$property->getName()] ?? null,
		];
		$dto = array_merge($dto, $this->parse($this->toAnnotation($property, 'var'), $class));
		switch ($dto['__type']) {
			case 'scalar':
				return ScalarProperty::create($dto);
			case 'class':
				return ClassProperty::create($dto);
			case 'interface':
				return InterfaceProperty::create($dto);
			case 'template':
				return TemplateProperty::create($dto);
			case 'generic':
				return GenericProperty::create($dto);
			case 'mixed':
				return MixedProperty::create($dto);
			case 'unknown':
				return UnknownProperty::create($dto);
		}

		throw new UnknownTypeException(sprintf('Cannot handle property [%s::$%s].', $class->getName(), $property->getName()));
	}

	/**
	 * @param ReflectionMethod $method
	 *
	 * @return MethodDto
	 *
	 * @throws MissingReflectionClassException
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 */
	public function toMethod(ReflectionMethod $method): MethodDto {
		$dto = [
			'name'        => $method->getName(),
			'annotations' => $this->toAnnotations($method),
		];
		$class = $method->getDeclaringClass();
		$return = $this->toAnnotation($method, 'return');
		$type = $method->getReturnType();
		$hasReturn = $return || $type;
		if ($method->getNumberOfParameters() === 0 && !$hasReturn) {
			return SimpleMethodDto::create($dto);
		} else if ($method->getNumberOfParameters() === 0 && $hasReturn) {
			return ResponseMethodDto::create(array_merge($dto, [
				'response' => $return ? $this->toTypeString($return, $class) : $this->toType($type, $class),
			]));
		} else if ($method->getNumberOfParameters() === 1 && !$hasReturn) {
			return RequestMethodDto::create(array_merge($dto, [
				'request' => $this->toParameter($method->getParameters()[0]),
			]));
		} else if ($method->getNumberOfParameters() === 1 && $hasReturn) {
			return RequestResponseMethodDto::create(array_merge($dto, [
				'request'  => $this->toParameter($method->getParameters()[0]),
				'response' => $return ? $this->toTypeString($return, $class) : $this->toType($type, $class),
			]));
		} else if ($method->getNumberOfParameters() > 1 && !$hasReturn) {
			$parameters = $this->toParameters($method);
			return ParameterMethodDto::create(array_merge($dto, [
				'request'    => reset($parameters),
				'parameters' => $parameters,
			]));
		} else if ($method->getNumberOfParameters() > 1 && $hasReturn) {
			$parameters = $this->toParameters($method);
			return ParameterResponseMethodDto::create(array_merge($dto, [
				'request'    => reset($parameters),
				'response'   => $return ? $this->toTypeString($return, $class) : $this->toType($type, $class),
				'parameters' => $parameters,
			]));
		}

		return MethodDto::create($dto);
	}

	/**
	 * @param ReflectionClass $class
	 *
	 * @return MethodDto[]
	 *
	 * @throws MissingReflectionClassException
	 * @throws ReflectionException
	 * @throws UnknownTypeException
	 */
	public function toMethods(ReflectionClass $class): array {
		return array_combine(array_map(function (ReflectionMethod $method) {
			return $method->getName();
		}, $class->getMethods(ReflectionMethod::IS_PUBLIC)), array_map(function (ReflectionMethod $method) {
			return $this->toMethod($method);
		}, $class->getMethods(ReflectionMethod::IS_PUBLIC)));
	}

	public function toAnnotation(Reflector $reflector, string $name, $default = null) {
		return $this->reader->getAnnotations($reflector)->get($name, $default);
	}

	public function toAnnotations(Reflector $reflector): array {
		return $this->reader->getAnnotations($reflector)->toArray();
	}

	public function toUse(ReflectionClass $class, string $type): string {
		return Reflection::expandClassName($type, $class);
	}

	public function toModule(string $name): string {
		return implode('/', array_map(function (string $part) {
				return StringUtils::recamel($part);
			}, explode('\\', $name))) . '/index';
	}
}
