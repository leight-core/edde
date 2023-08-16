<?php
declare(strict_types=1);

namespace Edde\Dto;

use Edde\Dto\Exception\SmartDtoException;
use Edde\Mapper\IMapper;
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
	 * @var IMapper
	 */
	protected $input;
	protected $inputParams;
	/**
	 * @var IMapper
	 */
	protected $output;
	protected $outputParams;
	protected $outputCache;
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
	public function __construct(ISchema $schema, IAttribute $attribute, IMapper $input, IMapper $output) {
		$this->schema = $schema;
		$this->attribute = $attribute;
		$this->input = $input;
		$this->output = $output;
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
		$this->value = $this->input->item($value, $this->inputParams);
		$this->isUndefined = false;
		return $this;
	}

	public function get() {
		/**
		 * Mapper can return object, so keep the same instance (and in general, prevent overcomputing here).
		 */
		return $this->outputCache ?? $this->outputCache = $this->output->item($this->getRaw(), $this->outputParams);
	}

	public function resolve(): self {
		if ($this->value !== ($get = $this->get())) {
			$this->set($get);
		}
		return $this;
	}

	public function getRaw() {
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
		if (is_array($this->value)) {
			foreach ($this->value as $value) {
				$value instanceof SmartDto && $value->validate();
			}
		} else if ($this->value instanceof SmartDto) {
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

	public function withInput(IMapper $input): self {
		$this->input = $input;
		return $this;
	}

	public function withInputParams($params): self {
		$this->inputParams = $params;
		return $this;
	}

	public function withOutput(IMapper $output): self {
		$this->output = $output;
		return $this;
	}

	public function withOutputParams($params): self {
		$this->outputParams = $params;
		return $this;
	}
}
