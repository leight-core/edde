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

	/**
	 * Check if the given SmartDto satisfies the given schema; exception is thrown or the same object is returned
	 *
	 * You can see this method as kind of deep "type-check" PHP does not have (in runtime).
	 *
	 * @param SmartDto $dto
	 * @param string   $schema
	 *
	 * @return SmartDto
	 */
	public function check(SmartDto $dto, string $schema): SmartDto;
}
