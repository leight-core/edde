<?php
declare(strict_types=1);

namespace Edde\Dto;

use Edde\Container\ContainerTrait;
use Edde\Mapper\MapperServiceTrait;
use Edde\Schema\ISchema;
use Edde\Schema\SchemaManagerTrait;

class SmartService implements ISmartService {
	use SchemaManagerTrait;
	use ContainerTrait;
	use MapperServiceTrait;

	public function create(string $schema): SmartDto {
		return $this->createFromSchema($this->schemaManager->load($schema));
	}

	public function createFromSchema(ISchema $schema): SmartDto {
		return SmartDto::ofSchema($schema, $this->mapperService);
	}

	public function from($object, string $schema): SmartDto {
		return $this->fromSchema($object, $this->schemaManager->load($schema));
	}

	public function fromSchema($object, ISchema $schema): SmartDto {
		return $this->createFromSchema($schema)->from($object);
	}

	public function check(SmartDto $dto, string $schema): SmartDto {
		/**
		 * The trick is simple: export DTO and import it using the given schema; if there is something
		 * wrong, proper schema exception is thrown, thus it's not necessary to try-catch here.
		 */
		$this->from($dto->export(), $schema);
		return $dto;
	}

	public function cloneTo(SmartDto $dto, string $schema, $merge = null): SmartDto {
		return $this->from(
			array_merge(
				(array)$dto->export(true),
				(array)$merge ?? []
			),
			$schema
		);
	}
}
