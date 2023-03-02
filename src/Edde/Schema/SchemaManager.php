<?php
declare(strict_types=1);

namespace Edde\Schema;

class SchemaManager implements ISchemaManager {
	use SchemaLoaderTrait;

	/** @var ISchema[] */
	protected $schemas = [];

	/** @inheritdoc */
	public function load(string $name): ISchemaManager {
		if (isset($this->schemas[$name]) !== false) {
			return $this;
		}
		$this->schemas[$name] = $this->schemaLoader->load($name);
		return $this;
	}

	/** @inheritdoc */
	public function loads(array $names): ISchemaManager {
		foreach ($names as $name) {
			$this->load($name);
		}
		return $this;
	}

	/** @inheritdoc */
	public function hasSchema(string $name): bool {
		return isset($this->schemas[$name]);
	}

	/** @inheritdoc */
	public function getSchema(string $name): ISchema {
		if (isset($this->schemas[$name]) === false) {
			throw new SchemaException(sprintf('Requested schema [%s] is not loaded; try to use [%s::load("%s")].', $name, ISchemaManager::class, $name));
		}
		return $this->schemas[$name];
	}

	/** @inheritdoc */
	public function getSchemas(): array {
		return $this->schemas;
	}
}
