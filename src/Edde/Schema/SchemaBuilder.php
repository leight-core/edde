<?php
declare(strict_types=1);

namespace Edde\Schema;

use stdClass;

class SchemaBuilder implements ISchemaBuilder {
	/** @var stdClass */
	protected $source;
	/** @var IAttributeBuilder[] */
	protected $attributeBuilders = [];
	/** @var ISchema */
	protected $schema;

	public function __construct(string $name) {
		$this->source = (object)['name' => $name];
	}

	/** @inheritdoc */
	public function meta(array $meta): ISchemaBuilder {
		$this->source->meta = $meta;
		return $this;
	}

	/** @inheritdoc */
	public function attribute(string $name): IAttributeBuilder {
		return $this->attributeBuilders[$name] ?? $this->attributeBuilders[$name] = new AttributeBuilder($name);
	}

	/** @inheritdoc */
	public function create(): ISchema {
		if ($this->schema) {
			return $this->schema;
		}
		$attributes = [];
		foreach ($this->attributeBuilders as $name => $propertyBuilder) {
			$attributes[$name] = $propertyBuilder->getAttribute();
		}
		return $this->schema = new Schema(
			$this->source,
			$attributes
		);
	}
}
