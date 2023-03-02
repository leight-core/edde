<?php
declare(strict_types=1);

namespace Edde\Schema;

trait SchemaManagerTrait {
	/** @var ISchemaManager */
	protected $schemaManager;

	/**
	 * @Inject
	 *
	 * @param ISchemaManager $schemaManager
	 */
	public function setSchemaManager(ISchemaManager $schemaManager): void {
		$this->schemaManager = $schemaManager;
	}
}
