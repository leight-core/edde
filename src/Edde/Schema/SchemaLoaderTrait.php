<?php
declare(strict_types=1);

namespace Edde\Schema;

trait SchemaLoaderTrait {
	/** @var ISchemaLoader */
	protected $schemaLoader;

	/**
	 * @Inject
	 *
	 * @param ISchemaLoader $schemaLoader
	 */
	public function setSchemaLoader(ISchemaLoader $schemaLoader): void {
		$this->schemaLoader = $schemaLoader;
	}
}
