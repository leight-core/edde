<?php
declare(strict_types=1);

namespace Edde\Schema;

interface ISchemaManager {
	/**
	 * load the given schema; no schema on return is intentional as it's not indented to use this
	 * method to get schemas
	 *
	 * @param string $name
	 *
	 * @return ISchemaManager
	 *
	 * @throws SchemaException
	 */
	public function load(string $name): ISchema;

	/**
	 * just array of schemas to be loaded
	 *
	 * @param string[] $names
	 *
	 * @return ISchema[]
	 *
	 * @throws SchemaException
	 */
	public function loads(array $names): array;

	/**
	 * is the given schema available?
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasSchema(string $name): bool;

	/**
	 * return all known schemas
	 *
	 * @return ISchema[]
	 */
	public function getSchemas(): array;
}
