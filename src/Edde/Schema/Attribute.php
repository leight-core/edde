<?php
declare(strict_types=1);

namespace Edde\Schema;

use stdClass;
use function property_exists;

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
		return isset($this->source->validator) ? (string)$this->source->validator : null;
	}

	/** @inheritdoc */
	public function getDefault() {
		return $this->source->default ?? null;
	}

	/** @inheritdoc */
	public function getFilter(string $name): ?string {
		return isset($this->source->filters[$name]) ? (string)$this->source->filters[$name] : null;
	}

	public function isArray(): bool {
		return $this->source->array;
	}

	/** @inheritdoc */
	public function hasSchema(): bool {
		return property_exists($this->source, 'schema') !== false && $this->source->schema !== null;
	}

	/** @inheritdoc */
	public function getSchema(): ISchema {
		if ($this->hasSchema() === false) {
			throw new SchemaException(sprintf('Property [%s] does not have a reference to schema.', $this->getName()));
		}
		return $this->source->schema;
	}
}
