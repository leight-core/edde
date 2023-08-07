<?php
declare(strict_types=1);

namespace Edde\Schema;

use stdClass;

class AttributeBuilder implements IAttributeBuilder {
	/** @var stdClass */
	protected $source;
	/** @var IAttribute */
	protected $attribute;

	public function __construct(string $name) {
		$this->source = (object)[
			'name'       => $name,
			'required'   => false,
			'default'    => null,
			'array'      => false,
			'load'       => false,
			'schema'     => null,
			'instanceOf' => null,
		];
	}

	/** @inheritdoc */
	public function type(string $type): IAttributeBuilder {
		$this->source->type = $type;
		return $this;
	}

	/** @inheritdoc */
	public function required(bool $required = true): IAttributeBuilder {
		$this->source->required = $required;
		return $this;
	}

	/** @inheritdoc */
	public function default($default): IAttributeBuilder {
		$this->source->default = $default;
		return $this;
	}

	public function array(bool $array = true): IAttributeBuilder {
		$this->source->array = $array;
		return $this;
	}

	public function load(bool $load = true): IAttributeBuilder {
		$this->source->load = $load;
		return $this;
	}

	public function schema(ISchema $schema): IAttributeBuilder {
		$this->source->schema = $schema;
		return $this;
	}

	public function instanceOf(string $class): IAttributeBuilder {
		$this->source->instanceOf = $class;
		return $this;
	}

	/** @inheritdoc */
	public function getAttribute(): IAttribute {
		return $this->attribute ?: $this->attribute = new Attribute($this->source);
	}
}
