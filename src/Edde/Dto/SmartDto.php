<?php
declare(strict_types=1);

namespace Edde\Dto;

use Edde\Dto\Exception\SmartDtoException;
use Edde\Schema\IAttribute;
use Edde\Schema\ISchema;
use Edde\Schema\Schema;
use Edde\Schema\SchemaException;
use Generator;
use IteratorAggregate;
use ReflectionClass;
use ReflectionException;
use stdClass;
use Traversable;

class SmartDto implements IDto, IteratorAggregate {
	/**
	 * @var ISchema
	 */
	protected $schema;
	/**
	 * Key-value. Literally.
	 *
	 * @var Value[]
	 */
	protected $values;

	/**
	 * @param ISchema $schema
	 * @param Value[] $values
	 */
	public function __construct(ISchema $schema, array $values) {
		$this->schema = $schema;
		$this->values = $values;
	}

	public function getName(): string {
		return $this->schema->getName();
	}

	public function getSchema(): ISchema {
		return $this->schema;
	}

	/**
	 * Is the property known to this DTO?
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function known(string $name): bool {
		return isset($this->values[$name]);
	}

	/**
	 * Tells if the given value has not been provided (regardless of a value).
	 *
	 * @param string $name
	 *
	 * @return bool
	 *
	 * @throws SmartDtoException
	 */
	public function isUndefined(string $name): bool {
		return $this->get($name)->isUndefined();
	}

	/**
	 * @param string $name
	 *
	 * @return Value
	 * @throws SmartDtoException
	 */
	public function get(string $name): Value {
		if (!$this->known($name)) {
			throw new SmartDtoException(sprintf("Requested unknown property [%s] on [%s].", $name, $this->schema->getName()));
		}
		return $this->values[$name];
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 * @throws SmartDtoException
	 */
	public function getValue(string $name) {
		return $this->get($name)->get();
	}

	public function getSafeValue(string $name, $default) {
		try {
			return $this->getValue($name);
		} catch (SmartDtoException $exception) {
			return $default;
		}
	}

	public function getValueOrThrow(string $name) {
		if (($value = $this->getValue($name)) === null) {
			throw new SmartDtoException(sprintf("Requested value [%s::%s] is not set (=== null).", $this->schema->getName(), $name));
		}
		return $value;
	}

	/**
	 * If you expect SmartDto value, this methods ensures you'll get it. Or an Exception
	 *
	 * @param string $name
	 *
	 * @return SmartDto|null
	 */
	public function getSmartDto(string $name): ?SmartDto {
		try {
			if (!$this->known($name)) {
				return null;
			}
			return $this->getSmartDtoOrThrow($name);
		} catch (SmartDtoException $exception) {
			return null;
		}
	}

	public function getSmartDtoOrThrow(string $name): SmartDto {
		if (!($dto = $this->getValueOrThrow($name)) instanceof SmartDto) {
			throw new SmartDtoException(sprintf('Requested value [%s::%s] is not SmartDto object.', $this->schema->getName(), $name));
		}
		return $dto;
	}

	/**
	 * @param string $name
	 * @param        $value
	 *
	 * @return $this
	 * @throws SmartDtoException
	 */
	public function set(string $name, $value): self {
		$this->get($name)->set($value);
		return $this;
	}

	/**
	 * Merge the given object/array into this smart dto; if there is an unknown property,
	 * an exception is thrown.
	 *
	 * @param array|object $values
	 *
	 * @return $this
	 *
	 * @throws SmartDtoException
	 */
	public function merge($values): self {
		foreach ($values as $k => $v) {
			$this->set($k, $v);
		}
		return $this;
	}

	public function isValid(): bool {
		foreach ($this->values as $value) {
			if (!$value->isValid()) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @return $this
	 *
	 * @throws SmartDtoException
	 */
	public function validate(): self {
		foreach ($this->values as $value) {
			$value->validate();
		}
		return $this;
	}

	/**
	 * Merge known properties into the object; there is no validation running
	 * in this method, so it's up to the developer to ensure here are data he
	 * expects.
	 *
	 * This method does recursive parsing, so when there is a property having a Schema,
	 * new SmartDTO will be created & populated.
	 *
	 * @param object $object
	 *
	 * @return $this
	 * @throws SmartDtoException
	 * @throws SchemaException
	 */
	public function from(object $object): self {
		/**
		 * Running on an object side instead of "values" side is because
		 * $object could be partial, thus rendering some of the "values" as "undefined".
		 *
		 * If it would be from the other side, all "values" would be set to a value or null,
		 * effectively removing meaning of "undefined".
		 */
		foreach ($object as $k => $v) {
			if (!$this->known($k)) {
				continue;
			}
			$value = $this->get($k);
			if ($v !== null && ($attribute = $value->getAttribute())->hasSchema() && ($schema = $attribute->getSchema())) {
				if ($attribute->isArray()) {
					$array = [];
					foreach ($v as $_k => $_v) {
						$array[$_k] = self::ofSchema($schema)->from($_v);
					}
					$v = $array;
				} else {
					$v = self::ofSchema($schema)->from($v);
				}
			}
			$this->set($k, $v);
		}
		return $this;
	}

	/**
	 * Merge non-undefined values into to given object (on a property level).
	 *
	 * @template T of object
	 *
	 * @param T $object
	 *
	 * @return T
	 *
	 * @throws ReflectionException
	 * @throws SmartDtoException
	 */
	public function exportTo($object): object {
		$reflection = new ReflectionClass($object);
		foreach ($this->values as $k => $value) {
			if ($value->isUndefined() || !$reflection->hasProperty($k)) {
				continue;
			}
			$attribute = $value->getAttribute();
			if ($attribute->hasInstanceOf() && !empty($object->$k) && !is_a($object->$k, $attribute->getInstanceOf())) {
				throw new SmartDtoException(sprintf("Property [%s::%s] instanceOf mismatch: schema [%s], present [%s].", $this->schema->getName(), $k, $attribute->getInstanceOf(), get_class($object->$k)));
			}
			$set = $value->get();
			if ($attribute->hasInstanceOf() && $set instanceof SmartDto) {
				$set->exportTo($set = empty($object->$k) ? (new ReflectionClass($attribute->getInstanceOf()))->newInstance() : $object->$k);
			}
			$property = $reflection->getProperty($k);
			$property->setAccessible(true);
			$property->setValue($object, $set);
		}
		return $object;
	}

	public function export(): object {
		return (object)iterator_to_array($this->getValues());
	}

	/**
	 * Creates the given instance and fill all non-undefined values; same as mergeTo(new $instanceOf).
	 *
	 * Constructor is being called.
	 *
	 * @template T
	 *
	 * @param callable-string $instanceOf
	 *
	 * @return T Newly created instance
	 *
	 * @throws ReflectionException
	 * @throws SmartDtoException
	 */
	public function instanceOf(string $instanceOf): object {
		$reflection = new ReflectionClass($instanceOf);
		$this->exportTo($target = $reflection->newInstance());
		return $target;
	}

	/**
	 * Return all non-undefined values
	 *
	 * @return Value[]|Generator|Traversable
	 */
	public function getValues() {
		foreach ($this->values as $k => $value) {
			if ($value->isUndefined()) {
				continue;
			}
			$attribute = $value->getAttribute();
			$v = $value->get();
			if ($v instanceof SmartDto) {
				$v = $v->export();
			} else if ($attribute->isArray()) {
				$v = array_map(function ($item) {
					return $item instanceof SmartDto ? $item->export() : $item;
				}, $v);
			}
			yield $k => $v;
		}
	}

	/**
	 * @return Value[]|Generator|Traversable
	 */
	public function getIterator() {
		foreach ($this->values as $value) {
			yield $value;
		}
	}

	static public function ofSchema(ISchema $schema): self {
		return new self(
			$schema,
			array_map(
				function (IAttribute $attribute) use ($schema) {
					return new Value($schema, $attribute);
				},
				$schema->getAttributes()
			)
		);
	}

	static public function ofDummy(): self {
		return new self(new Schema(new stdClass(), []), []);
	}
}
