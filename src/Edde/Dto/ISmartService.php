<?php
declare(strict_types=1);

namespace Edde\Dto;

use Edde\Schema\ISchema;

interface ISmartService {
	/**
	 * Creates an empty SmartDto
	 *
	 * @param string $schema
	 *
	 * @return SmartDto
	 */
	public function create(string $schema): SmartDto;

	/**
	 * @param ISchema $schema
	 *
	 * @return SmartDto
	 */
	public function createFromSchema(ISchema $schema): SmartDto;

	/**
	 * Creates smart dto from the given object using provided shape; the shape
	 * controls properties and other stuff an $object must provide/fulfill.
	 *
	 * @param object $object
	 * @param string $schema
	 *
	 * @return SmartDto
	 */
	public function from(object $object, string $schema): SmartDto;

	public function fromSchema(object $object, ISchema $schema): SmartDto;
}
