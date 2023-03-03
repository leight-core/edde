<?php
declare(strict_types=1);

namespace Edde\Dto;

use Edde\Dto\Exception\SmartDtoException;
use Edde\Schema\IAttribute;
use Edde\Schema\ISchema;

/**
 * Value of a smart object; because PHP does not have an undefined, this class somehow solves it
 * by managing that state: if value on the input does not exists, it can return null (or default),
 * but also knows if was provided.
 */
class Value {
	/** @var ISchema */
	protected $schema;
	/**
	 * @var IAttribute
	 */
	protected $attribute;
	/**
	 * Undefined state changes in the moment, when any value (including false, null and so on) is
	 * set. If you need to mark value as undefined, use explicit method for it.
	 *
	 * @var bool
	 */
	protected $isUndefined = true;
	/**
	 * A value of this... value; may be null or something
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * @param ISchema    $schema
	 * @param IAttribute $attribute
	 */
	public function __construct(ISchema $schema, IAttribute $attribute) {
		$this->schema = $schema;
		$this->attribute = $attribute;
	}

	/**
	 * @return IAttribute
	 */
	public function getAttribute(): IAttribute {
		return $this->attribute;
	}

	/**
	 * Set any value; this will change "undefined" flag regardless of the provided value (so null also clears
	 * undefined flag).
	 *
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function set($value): self {
		$this->value = $value;
		$this->isUndefined = false;
		return $this;
	}

	public function get() {
		return $this->isUndefined ? $this->attribute->getDefault() : $this->value;
	}

	public function isValid(): bool {
		try {
			$this->validate();
			return true;
		} catch (SmartDtoException $exception) {
			return false;
		}
	}

	/**
	 * @return $this
	 *
	 * @throws SmartDtoException
	 */
	public function validate(): self {
		/**
		 * Required & null is valid state
		 */
		if ($this->value === null && !$this->attribute->isRequired()) {
			return $this;
		}
		/**
		 * Even when there could be a default value, undefined means "no value" from the user side,
		 * so it's evaluated as "not valid".
		 */
		if ($this->isUndefined && $this->attribute->isRequired()) {
			throw new SmartDtoException(sprintf("Value [%s::%s] is required, but is undefined (default value from attribute is omitted).", $this->schema->getName(), $this->attribute->getName()));
		}
		if ($this->value !== null && $this->attribute->isArray() && !is_array($this->value)) {
			throw new SmartDtoException(sprintf("Value [%s::%s] is present, but is not an array.", $this->schema->getName(), $this->attribute->getName()));
		}
		if ($this->value instanceof SmartDto) {
			$this->value->validate();
		}
		return $this;
	}

	public function isUndefined(): bool {
		return $this->isUndefined;
	}

	/**
	 * Marks value as undefined (change the state); also sets $value to null.
	 */
	public function undefined(): self {
		$this->isUndefined = true;
		$this->value = null;
		return $this;
	}
}
