<?php
declare(strict_types=1);

namespace Edde\Dto;

use Edde\Schema\ISchema;
use Edde\Schema\SchemaException;
use Edde\Schema\SchemaManagerTrait;

class SmartService implements ISmartService {
	use SchemaManagerTrait;

	/**
	 * @param string $schema
	 *
	 * @return SmartDto
	 *
	 * @throws SchemaException
	 */
	public function create(string $schema): SmartDto {
		return $this->createFromSchema($this->schemaManager->load($schema));
	}

	public function createFromSchema(ISchema $schema): SmartDto {
		return SmartDto::ofSchema($schema);
	}

	/**
	 * @param object $object
	 * @param string $schema
	 *
	 * @return SmartDto
	 * @throws Exception\SmartDtoException
	 * @throws SchemaException
	 */
	public function from(object $object, string $schema): SmartDto {
		return $this->fromSchema($object, $this->schemaManager->load($schema));
	}

	/**
	 * @param object  $object
	 * @param ISchema $schema
	 *
	 * @return SmartDto
	 *
	 * @throws Exception\SmartDtoException
	 * @throws SchemaException
	 */
	public function fromSchema(object $object, ISchema $schema): SmartDto {
		return $this->createFromSchema($schema)->from($object);
	}

	public function check(SmartDto $dto, string $schema): SmartDto {
		/**
		 * The trick is simple: export DTO and import it using the given schema; if there is something
		 * wrong, proper schema exception is thrown, thus it's not necessary to try-catch here.
		 */
		$this->from($dto->getValues(), $schema);
		return $dto;
	}
}
