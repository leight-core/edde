<?php
declare(strict_types=1);

namespace Edde\Reflection;

use DateTime;
use DI\Annotation\Injectable;
use Edde\Cache\DatabaseCacheTrait;
use Edde\Dto\AbstractDtoService;
use Edde\Reflection\Dto\ClassDto;
use Edde\Reflection\Dto\Property\AbstractProperty;
use Edde\Reflection\Dto\Property\ClassProperty;
use Edde\Reflection\Exception\InvalidValueException;
use Edde\Reflection\Exception\MissingValueException;
use Edde\Reflection\Exception\UnknownTypeException;
use Exception;
use Psr\SimpleCache\InvalidArgumentException;
use ReflectionException as NativeReflectionException;

/**
 * This implementation uses in-house reflection to rebuild objects from an array.
 *
 * @Injectable(lazy=true)
 */
class ReflectionDtoService extends AbstractDtoService {
	use ReflectionServiceTrait;
	use DatabaseCacheTrait;

	/**
	 * @param string      $class
	 * @param object|null $source
	 *
	 * @return mixed
	 *
	 * @throws UnknownTypeException
	 * @throws InvalidArgumentException
	 * @throws InvalidValueException
	 * @throws MissingValueException
	 * @throws NativeReflectionException
	 */
	public function fromObject(string $class, ?object $source) {
		/**
		 * Be careful - this piece uses proprietary implementation used just in DatabaseCache - that's the reason CacheTrait is not
		 * used here.
		 *
		 * Also, Reflection Service should **not** use cache, so it's important to cache stuff on the caller site (here).
		 *
		 * @var $classDto ClassDto
		 */
		$classDto = $this->databaseCache->get('reflection.' . $class, function (string $key) use ($class) {
			$this->databaseCache->set($key, $value = $this->reflectionService->toClass($class));
			return $value;
		});
		$instance = new $class;
		foreach ($classDto->properties as $name => $propertyDto) {
			$instance->{$name} = $this->resolveValue($classDto, $propertyDto, @$source->{$name});
		}
		return $instance;
	}

	/**
	 * @param ClassDto         $classDto
	 * @param AbstractProperty $property
	 * @param mixed            $value
	 * @param bool             $inArray
	 *
	 * @return mixed
	 *
	 * @throws InvalidArgumentException
	 * @throws InvalidValueException
	 * @throws MissingValueException
	 * @throws NativeReflectionException
	 * @throws Exception
	 */
	protected function resolveValue(ClassDto $classDto, AbstractProperty $property, $value, bool $inArray = false) {
		if ($value === null && $property->isRequired && !$property->isOptional) {
			throw new MissingValueException(sprintf('Source for object [%s] is missing required value for property [%s].', $classDto->fqdn, $property->name));
		}
		if (!$inArray && $value !== null && $property->isArray && !is_array($value)) {
			throw new InvalidValueException(sprintf('Value for property [%s] of object [%s] is not an array.', $property->name, $classDto->fqdn));
		}
		if (!$inArray && $property->isArray && is_array($value)) {
			foreach ($value as $k => $v) {
				$value[$k] = $this->resolveValue($classDto, $property, $v, true);
			}
			return $value;
		}
		if ($property instanceof ClassProperty && !($value instanceof $property->class)) {
			switch ($property->class) {
				case DateTime::class:
					return $value ? new DateTime($value) : null;
				default:
					return is_object($value) ? $this->fromObject($property->class, $value) : $this->fromArray($property->class, $value);
			}
		}
		return $value;
	}
}
