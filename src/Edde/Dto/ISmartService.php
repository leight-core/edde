<?php
declare(strict_types=1);

namespace Edde\Dto;

use Edde\Dto\Exception\SmartDtoException;
use Edde\Schema\SchemaException;

interface ISmartService {
	/**
	 * @param string $schema
	 * @param array  $template
	 *
	 * @return SmartDto
	 * @throws SmartDtoException
	 * @throws SchemaException
	 */
	public function create(string $schema, array $template = []): SmartDto;

	/**
	 * Creates smart dto from the given object using provided shape; the shape
	 * controls properties and other stuff an $object must provide/fulfill.
	 *
	 * @param object|array $object
	 * @param string       $schema
	 * @param array        $template
	 *
	 * @return SmartDto
	 * @throws SmartDtoException
	 * @throws SchemaException
	 */
	public function from($object, string $schema, array $template = []): SmartDto;

	/**
	 * Check if the given SmartDto satisfies the given schema; exception is thrown or the same object is returned
	 *
	 * You can see this method as kind of deep "type-check" PHP does not have (in runtime).
	 *
	 * @param SmartDto $dto
	 * @param string   $schema
	 * @param array    $template
	 *
	 * @return SmartDto
	 * @throws SmartDtoException
	 * @throws SchemaException
	 */
	public function check(SmartDto $dto, string $schema, array $template = []): SmartDto;
}
