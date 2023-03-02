<?php
declare(strict_types=1);

namespace Edde\Schema;

class SchemaManager implements ISchemaManager {
	use SchemaLoaderTrait;

	/** @var ISchema[] */
	protected $schemas = [];

	/** @inheritdoc */
	public function load(string $name): ISchema {
		return $this->schemas[$name] ?? $this->schemas[$name] = $this->schemaLoader->load($name);
	}

	public function loads(array $names): array {
		$schemas = [];
		foreach ($names as $name) {
			$schemas[] = $this->load($name);
		}
		return $schemas;
	}

	/** @inheritdoc */
	public function hasSchema(string $name): bool {
		return isset($this->schemas[$name]);
	}

	/** @inheritdoc */
	public function getSchemas(): array {
		return $this->schemas;
	}
}
