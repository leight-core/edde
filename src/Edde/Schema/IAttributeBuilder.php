<?php
declare(strict_types=1);

namespace Edde\Schema;

interface IAttributeBuilder {
	/**
	 * set an attribute type
	 *
	 * @param string $type
	 *
	 * @return IAttributeBuilder
	 */
	public function type(string $type): IAttributeBuilder;

	/**
	 * set required flag
	 *
	 * @param bool $required
	 *
	 * @return IAttributeBuilder
	 */
	public function required(bool $required = true): IAttributeBuilder;

	/**
	 * set a default value for this attribute
	 *
	 * @param mixed $default
	 *
	 * @return IAttributeBuilder
	 */
	public function default($default): IAttributeBuilder;

	/**
	 * Marks value of an attribute as an array
	 *
	 * @param bool $array
	 *
	 * @return IAttributeBuilder
	 */
	public function array(bool $array = true): IAttributeBuilder;

	/**
	 * Tells an attribute it should load a schema.
	 *
	 * @param bool $load
	 *
	 * @return IAttributeBuilder
	 */
	public function load(bool $load = true): IAttributeBuilder;

	/**
	 * Set explicit reference to a schema.
	 *
	 * @param ISchema $schema
	 *
	 * @return IAttributeBuilder
	 */
	public function schema(ISchema $schema): IAttributeBuilder;

	/**
	 * creates and return a attribute
	 *
	 * @return IAttribute
	 */
	public function getAttribute(): IAttribute;
}
