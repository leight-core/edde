<?php
declare(strict_types=1);

namespace Edde\Dto;

use Edde\Schema\IAttribute;

/**
 * Value of a smart object; because PHP does not have an undefined, this class somehow solves it
 * by managing that state: if value on the input does not exists, it can return null (or default),
 * but also knows if was provided.
 */
class Value {
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
	 * @param IAttribute $attribute
	 */
	public function __construct(IAttribute $attribute) {
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
		/**
		 * Required & null is valid state
		 */
		if ($this->value === null && !$this->attribute->isRequired()) {
			return true;
		}
		/**
		 * Even when there could be a default value, undefined means "no value" from the user side,
		 * so it's evaluated as "not valid".
		 */
		if ($this->isUndefined && $this->attribute->isRequired()) {
			return false;
		}
		if ($this->value !== null && $this->attribute->isArray() && !is_array($this->value)) {
			return false;
		}
		return true;
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
