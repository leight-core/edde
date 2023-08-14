<?php
declare(strict_types=1);

namespace Edde\Schema;

/**
 * An attribute is "property" of schema which is basically
 * definition of a "real" property describing it's metadata.
 */
interface IAttribute {
	/**
	 * get attribute name
	 *
	 * @return string
	 */
	public function getName(): string;

	/**
	 * get type of an attribute
	 *
	 * @return string
	 */
	public function getType(): string;

	/**
	 * @return bool
	 */
	public function isRequired(): bool;

	/**
	 * @return mixed
	 */
	public function getDefault();

	/**
	 * @return bool
	 */
	public function isArray(): bool;

	/**
	 * Is target instance (class for schema) set?
	 *
	 * @return bool
	 */
	public function hasInstanceOf(): bool;

	/**
	 * Returns target object class for this property
	 *
	 * @return string
	 */
	public function getInstanceOf(): string;

	/**
	 * is this property reference to another schema?
	 *
	 * @return bool
	 */
	public function hasSchema(): bool;

	/**
	 * return target schema or thrown an exception if not available
	 *
	 * @return string
	 *
	 * @throws SchemaException
	 */
	public function getSchema(): ISchema;

	/**
	 * Return attribute's attached meta array if any
	 *
	 * @return array
	 */
	public function getMeta(): array;
}
