<?php
declare(strict_types=1);

namespace Edde\Schema;

use stdClass;

class Attribute implements IAttribute {
	/** @var stdClass */
	protected $source;

	public function __construct(stdClass $source) {
		$this->source = $source;
	}

	/** @inheritdoc */
	public function getName(): string {
		return (string)$this->source->name;
	}

	/** @inheritdoc */
	public function getType(): string {
		return (string)($this->source->type ?? 'string');
	}

	public function isRequired(): bool {
		return (bool)($this->source->required ?? false);
	}

	/** @inheritdoc */
	public function getValidator(): ?string {
		return $this->source->validator ?? null;
	}

	/** @inheritdoc */
	public function getDefault() {
		return $this->source->default ?? null;
	}

	/** @inheritdoc */
	public function getFilter(string $name): ?string {
		return $this->source->filters[$name] ?? null;
	}

	public function isArray(): bool {
		return $this->source->array;
	}

	public function hasInstanceOf(): bool {
		return isset($this->source->instanceOf);
	}

	public function getInstanceOf(): string {
		if (!$this->hasInstanceOf()) {
			throw new SchemaException(sprintf('Attribute [%s] does not have an instanceOf property.', $this->getName()));
		}
		return $this->source->instanceOf;
	}

	/** @inheritdoc */
	public function hasSchema(): bool {
		return isset($this->source->schema);
	}

	/** @inheritdoc */
	public function getSchema(): ISchema {
		if ($this->hasSchema() === false) {
			throw new SchemaException(sprintf('Attribute [%s] does not have a reference to schema.', $this->getName()));
		}
		return $this->source->schema;
	}

	public function getMeta(): array {
		return $this->source->meta;
	}
}
