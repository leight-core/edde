<?php
declare(strict_types=1);

namespace Edde\Dto;

use Edde\Schema\SchemaManagerTrait;

class SmartService implements ISmartService {
	use SchemaManagerTrait;

	public function create(string $schema): SmartDto {
		return SmartDto::ofSchema($this->schemaManager->load($schema));
	}

	public function from(object $object, string $schema): SmartDto {
		return $this->create($schema)->merge($object);
	}
}
