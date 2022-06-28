<?php
declare(strict_types=1);

namespace Edde\Reflection;

use DateTime;
use Edde\Cache\CacheTrait;
use Edde\Dto\AbstractDtoService;
use Edde\Reflection\Dto\ClassDto;
use Edde\Reflection\Dto\Property\AbstractProperty;
use Edde\Reflection\Dto\Property\ClassProperty;
use Edde\Reflection\Exception\InvalidValueException;
use Edde\Reflection\Exception\MissingValueException;
use Exception;
use Psr\SimpleCache\InvalidArgumentException;
use ReflectionException as NativeReflectionException;
use function is_array;
use function is_object;
use function sprintf;

/**
 * This implementation uses in-house reflection to rebuild objects from an array.
 */
class ReflectionDtoService extends AbstractDtoService {
	use ReflectionServiceTrait;
	use CacheTrait;

	/**
	 * @param string      $class
	 * @param object|null $source
	 *
	 * @return mixed
	 *
	 * @throws InvalidArgumentException
	 * @throws InvalidValueException
	 * @throws MissingValueException
	 * @throws NativeReflectionException
	 */
	public function fromObject(string $class, ?object $source, bool $allowNull = false) {
		if (empty($source)) {
			return null;
		}
		/**
		 * Be careful - this piece uses proprietary implementation used just in DatabaseCache - that's the reason CacheTrait is not
		 * used here.
		 *
		 * Also, Reflection Service should **not** use cache, so it's important to cache stuff on the caller site (here).
		 *
		 * @var $classDto ClassDto
		 */
		$classDto = $this->cache->get('reflection.' . $class, function (string $key) use ($class) {
			$this->cache->set($key, $value = $this->reflectionService->toClass($class));
			return $value;
		});
		$instance = new $class;
		$object = [];
		foreach ($classDto->properties as $name => $propertyDto) {
			if (($value = $source->{$class . '.' . $name} ?? $source->{$name} ?? null) !== null) {
				$object[$name] = $value;
			}
		}
		if ($allowNull && empty($object)) {
			return null;
		}
		foreach ($classDto->properties as $name => $propertyDto) {
			$instance->{$name} = $this->resolveValue($classDto, $propertyDto, $object[$name] ?? null);
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
		$value = $value ?? $property->value;
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
