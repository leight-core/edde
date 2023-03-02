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
	 * @param string $type
	 * @param string $filter
	 *
	 * @return IAttributeBuilder
	 */
	public function filter(string $type, string $filter): IAttributeBuilder;

	/**
	 * set a validator for this attribute
	 *
	 * @param string $validator
	 *
	 * @return IAttributeBuilder
	 */
	public function validator(string $validator): IAttributeBuilder;

	/**
	 * set a default value for this attribute
	 *
	 * @param mixed $default
	 *
	 * @return IAttributeBuilder
	 */
	public function default($default): IAttributeBuilder;

	/**
	 * attribute is a reference to another schema
	 *
	 * @param string $schema
	 *
	 * @return IAttributeBuilder
	 */
	public function schema(string $schema): IAttributeBuilder;

	/**
	 * creates and return a attribute
	 *
	 * @return IAttribute
	 */
	public function getAttribute(): IAttribute;
}
