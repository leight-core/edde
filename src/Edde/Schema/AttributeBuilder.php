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
			'meta' => [],
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

	public function meta(array $meta): IAttributeBuilder {
		$this->source->meta = $meta;
		return $this;
	}

	public function input(array $input): IAttributeBuilder {
		$this->source->input = $input;
		return $this;
	}

	public function output(array $output): IAttributeBuilder {
		$this->source->output = $output;
		return $this;
	}

	/** @inheritdoc */
	public function getAttribute(): IAttribute {
		return $this->attribute ?: $this->attribute = new Attribute($this->source);
	}
}
