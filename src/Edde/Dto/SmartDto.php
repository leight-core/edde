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
			throw new SmartDtoException(sprintf("Requested unknown property [%s] on [%s]." . $name, $this->schema->getName()));
		}
		return $this->values[$name];
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
	public function merge(object $object): self {
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
			if ($value->getAttribute()->hasSchema() && $schema = $value->getAttribute()->getSchema()) {
				$v = self::ofSchema($schema)->merge($v);
			}

			$this->known($k) && $this->set($k, $v);
		}
		return $this;
	}

	/**
	 * Merge non-undefined values into to given object (on a property level).
	 *
	 * @param object $object
	 *
	 * @return object
	 */
	public function mergeTo(object $object): object {
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
