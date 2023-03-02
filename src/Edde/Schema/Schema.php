<?php
declare(strict_types=1);

namespace Edde\Schema;

use stdClass;

class Schema implements ISchema {
	/** @var stdClass */
	protected $source;
	/** @var IAttribute[] */
	protected $attributes = [];

	public function __construct(stdClass $source, array $attributes) {
		$this->source = $source;
		$this->attributes = $attributes;
	}

	/** @inheritdoc */
	public function getName(): string {
		return (string)$this->source->name;
	}

	/** @inheritdoc */
	public function getMeta(string $name, $default = null) {
		return $this->source->meta[$name] ?? $default;
	}

	/** @inheritdoc */
	public function getAttribute(string $name): IAttribute {
		if (isset($this->attributes[$name]) === false) {
			throw new SchemaException(sprintf('Requested unknown attribute [%s::%s].', $this->getName(), $name));
		}
		return $this->attributes[$name];
	}

	/** @inheritdoc */
	public function getAttributes(): array {
		return $this->attributes;
	}
}
