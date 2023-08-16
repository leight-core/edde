<?php
declare(strict_types=1);

namespace Edde\Dto;

use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\Mapper\SmartDtoMapper;
use Edde\Mapper\IMapper;
use Edde\Mapper\IMapperService;
use Edde\Mapper\MapperServiceTrait;
use Edde\Mapper\NoopMapper;
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
	use MapperServiceTrait;

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
	protected function __construct(ISchema $schema, array $values) {
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

	public function getSafeValue(string $name, $default = null) {
		try {
			return $this->getValue($name);
		} catch (SmartDtoException $exception) {
			return $default;
		}
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 * @throws SmartDtoException
	 */
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
	public function getSmartDto(string $name): SmartDto {
		try {
			if (!$this->known($name)) {
				return self::ofDummy();
			}
			return $this->getSmartDtoOrThrow($name);
		} catch (SmartDtoException $exception) {
			return self::ofDummy();
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
		if (!$values) {
			return $this;
		}
		foreach ($values as $k => $v) {
			$this->set($k, $v);
		}
		return $this;
	}

	/**
	 * Merge the given dto into current one and apply $merge if provided.
	 *
	 * @param SmartDto     $dto
	 * @param object|array $merge
	 *
	 * @return $this
	 * @throws SmartDtoException
	 */
	public function mergeWith(SmartDto $dto, $merge = null): self {
		foreach ($dto->export(true) as $k => $v) {
			$this->set($k, $v);
		}
		return $this->merge($merge);
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
	 * Ensure the given values are known and not undefined.
	 *
	 * @param array $values
	 *
	 * @return self
	 * @throws SmartDtoException
	 */
	public function ensure(array $values): self {
		foreach ($values as $v) {
			if (!$this->known($v)) {
				throw new SmartDtoException(sprintf('SmartDto [%s] is missing property [%s].', $this->getName(), $v));
			} else if ($this->isUndefined($v)) {
				throw new SmartDtoException(sprintf('SmartDto [%s] has property [%s], but it is undefined.', $this->getName(), $v));
			}
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
	 * @param object|array $object
	 *
	 * @return $this
	 * @throws SmartDtoException
	 * @throws SchemaException
	 */
	public function from($object): self {
		/**
		 * Running on an object side instead of "values" side is because
		 * $object could be partial, thus rendering some of the "values" as "undefined".
		 *
		 * If it would be from the other side, all "values" would be set to a value or null,
		 * effectively removing meaning of "undefined".
		 */
		foreach ($object instanceof SmartDto ? $object->export(true) : $object as $k => $v) {
			if (!$this->known($k)) {
				continue;
			}
			$value = $this->get($k);
			if ($v !== null && ($attribute = $value->getAttribute())->hasSchema() && ($schema = $attribute->getSchema())) {
				if ($attribute->isArray()) {
					$array = [];
					foreach ($v as $_k => $_v) {
						$array[$_k] = self::ofSchema($schema, $this->mapperService)->from($_v);
					}
					$v = $array;
				} else {
					$v = self::ofSchema($schema, $this->mapperService)->from($v);
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
	public function exportTo(object $object, bool $raw = false): object {
		$reflection = new ReflectionClass($object);
		foreach ($this->values as $k => $value) {
			if ($value->isUndefined() || !$reflection->hasProperty($k)) {
				continue;
			}
			$attribute = $value->getAttribute();
			if ($attribute->hasInstanceOf() && !empty($object->$k) && !is_a($object->$k, $attribute->getInstanceOf())) {
				throw new SmartDtoException(sprintf("Property [%s::%s] instanceOf mismatch: schema [%s], present [%s].", $this->schema->getName(), $k, $attribute->getInstanceOf(), get_class($object->$k)));
			}
			$set = $raw ? $value->getRaw() : $value->get();
			if ($attribute->hasInstanceOf() && $set instanceof SmartDto) {
				$set->exportTo($set = empty($object->$k) ? (new ReflectionClass($attribute->getInstanceOf()))->newInstance() : $object->$k);
			}
			$property = $reflection->getProperty($k);
			$property->setAccessible(true);
			$property->setValue($object, $set);
		}
		return $object;
	}

	public function export(bool $raw = false): object {
		return (object)iterator_to_array($this->getValues($raw));
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
	public function instanceOf(string $instanceOf, bool $raw = false): object {
		$reflection = new ReflectionClass($instanceOf);
		$this->exportTo($target = $reflection->newInstance(), $raw);
		return $target;
	}

	/**
	 * Return all non-undefined values
	 *
	 * @return Value[]|Generator|Traversable
	 */
	public function getValues(bool $raw = false) {
		foreach ($this->values as $k => $value) {
			if ($value->isUndefined()) {
				continue;
			}
			$attribute = $value->getAttribute();
			$v = $raw ? $value->getRaw() : $value->get();
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

	public function convertTo(string $schema): self {
		return $this->mapperService->getMapper(SmartDtoMapper::class)->item($this->export(true), $schema);
	}

	public function withTemplate(array $template): self {
		foreach ($template as $name => $schema) {
			$value = $this->get($name);
			if (is_array($schema)) {
				if (!$value->getAttribute()->hasSchema()) {
					throw new SmartDtoException(sprintf('Setting template to a property [%s::%s] which is not SmartDto.', $this->getName(), $name));
				}
				$this->getSmartDto($name)->withTemplate($schema);
				continue;
			}
			$value
				->withOutput(
					$this->mapperService->getMapper(SmartDtoMapper::class)
				)
				->withOutputParams($schema);
		}
		return $this;
	}

	static public function ofSchema(ISchema $schema, IMapperService $mapperService): self {
		$dto = new self(
			$schema,
			array_map(
				function (IAttribute $attribute) use ($schema, $mapperService) {
					return new Value(
						$schema,
						$attribute,
						$mapperService->getMapper($attribute->getInput()),
						$mapperService->getMapper($attribute->getOutput())
					);
				},
				$schema->getAttributes()
			)
		);
		$dto->setMapperService($mapperService);
		return $dto;
	}

	static public function ofDummy(): self {
		$dto = new self(new Schema(new stdClass(), []), []);
		$dto->setMapperService(new class implements IMapperService {
			/**
			 * @var IMapper
			 */
			protected $noop;

			public function __construct() {
				$this->noop = new NoopMapper();
			}

			public function getMapper(?string $class): IMapper {
				return $this->noop;
			}
		});
		return $dto;
	}

	static public function exportOf($export) {
		if ($export === null) {
			return null;
		} else if (!$export instanceof SmartDto && !is_array($export)) {
			throw new SmartDtoException(sprintf('Exporting an unknown value of type [%s].', gettype($export)));
		} else if ($export instanceof SmartDto) {
			return $export->export();
		}
		return array_map(function ($item) {
			return $item instanceof SmartDto ? self::exportOf($item) : $item;
		}, $export);
	}
}
