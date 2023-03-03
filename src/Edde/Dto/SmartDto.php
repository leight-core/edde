<?php
declare(strict_types=1);

namespace Edde\Dto;

use Edde\Dto\Exception\SmartDtoException;
use Edde\Schema\IAttribute;
use Edde\Schema\ISchema;
use Edde\Schema\SchemaException;
use Generator;
use IteratorAggregate;
use ReflectionClass;
use ReflectionException;
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

	public function getValue(string $name) {
		return $this->get($name)->get();
	}

	public function getValueOrThrow(string $name) {
		if (($value = $this->getValue($name)) === null) {
			throw new SmartDtoException(sprintf("Requested value [%s::%s] is not set (=== null).", $this->schema->getName(), $name));
		}
		return $value;
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

	public function isValid(): bool {
		foreach ($this->values as $value) {
			if (!$value->isValid()) {
				return false;
			}
		}
		return true;
	}

	public function validate(): self {
		if (!$this->isValid()) {
			throw new SmartDtoException(sprintf("Smart DTO [%s] is not valid.", $this->schema->getName()));
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
	public function export(object $object): self {
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
			if (($attribute = $value->getAttribute())->hasSchema() && $schema = $attribute->getSchema()) {
				$v = $dto = self::ofSchema($schema)->export($v);
				if ($attribute->hasInstanceOf()) {
					$target = (new ReflectionClass($attribute->getInstanceOf()))->newInstance();
					$dto->exportTo($v = $target);
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
	 */
	public function exportTo($object): object {
		$reflection = new ReflectionClass($object);
		foreach ($this->values as $k => $value) {
			if ($value->isUndefined() || !$reflection->hasProperty($k)) {
				continue;
			}
			$property = $reflection->getProperty($k);
			$property->setAccessible(true);
			$property->setValue($object, $value->get());
		}
		return $object;
	}

	/**
	 * Creates the given instance and fill all non-undefined values; same as mergeTo(new $instanceOf).
	 *
	 * Constructor is being called.
	 *
	 * @template T
	 *
	 * @param callable-string<T> $instanceOf
	 *
	 * @return T Newly created instance
	 *
	 * @throws ReflectionException
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
			!$value->isUndefined() && yield $k => $value->get();
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
				function (IAttribute $attribute) {
					return new Value($attribute);
				},
				$schema->getAttributes()
			)
		);
	}
}
