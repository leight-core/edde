<?php
declare(strict_types=1);

namespace Edde\Dto;

/**
 * Value of a smart object; because PHP does not have an undefined, this class somehow solves it
 * by managing that state: if value on the input does not exists, it can return null (or default),
 * but also knows if was provided.
 */
class Value {
	/**
	 * Undefined state changes in the moment, when any value (including false, null and so on) is
	 * set. If you need to mark value as undefined, use explicit method for it.
	 *
	 * @var bool
	 */
	protected $isUndefined = true;
	/**
	 * Default value when value is undefined (controlled by value state).
	 *
	 * @var mixed
	 */
	protected $default;
	/**
	 * A value of this... value; may be null or something
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * @param mixed $default
	 */
	public function __construct($default) {
		$this->default = $default;
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
		return $this->isUndefined ? $this->default : $this->value;
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
