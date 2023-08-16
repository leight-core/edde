<?php
declare(strict_types=1);

namespace Edde\Dto;

use Edde\Container\ContainerTrait;
use Edde\Mapper\MapperServiceTrait;
use Edde\Schema\ISchema;
use Edde\Schema\SchemaException;
use Edde\Schema\SchemaManagerTrait;

class SmartService implements ISmartService {
	use SchemaManagerTrait;
	use ContainerTrait;
	use MapperServiceTrait;

	public function create(string $schema, array $template = []): SmartDto {
		return $this->createFromSchema(
			$this->schemaManager->load($schema),
			$template
		);
	}

	public function from($object, string $schema, array $template = []): SmartDto {
		return $this->fromSchema(
			$object,
			$this->schemaManager->load($schema),
			$template
		);
	}

	public function check(SmartDto $dto, string $schema, array $template = []): SmartDto {
		/**
		 * The trick is simple: export DTO and import it using the given schema; if there is something
		 * wrong, proper schema exception is thrown, thus it's not necessary to try-catch here.
		 */
		$this->from($dto->export(), $schema, $template)->validate();
		return $dto;
	}

	/**
	 * @param         $object
	 * @param ISchema $schema
	 * @param array   $template
	 *
	 * @return SmartDto
	 * @throws Exception\SmartDtoException
	 * @throws SchemaException
	 */
	protected function fromSchema($object, ISchema $schema, array $template = []): SmartDto {
		return $this->createFromSchema($schema, $template)->from($object);
	}

	/**
	 * @param ISchema $schema
	 * @param array   $template
	 *
	 * @return SmartDto
	 * @throws Exception\SmartDtoException
	 */
	protected function createFromSchema(ISchema $schema, array $template = []): SmartDto {
		return SmartDto::ofSchema($schema, $this->mapperService)->withTemplate($template);
	}
}
