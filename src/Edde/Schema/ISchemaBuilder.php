<?php
declare(strict_types=1);

namespace Edde\Schema;

interface ISchemaBuilder {
	/**
	 * set meta data for the schema
	 *
	 * @param array $meta
	 *
	 * @return ISchemaBuilder
	 */
	public function meta(array $meta): ISchemaBuilder;

	/**
	 * Is the given attribute builder present?
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function has(string $name): bool;

	/**
	 * create a new attribute with the given name
	 *
	 * @param string $name
	 *
	 * @return IAttributeBuilder
	 */
	public function attribute(string $name): IAttributeBuilder;

	/**
	 * @return IAttributeBuilder[]
	 */
	public function attributes(): array;

	/**
	 * build and return a schema
	 *
	 * @return ISchema
	 */
	public function create(): ISchema;
}
