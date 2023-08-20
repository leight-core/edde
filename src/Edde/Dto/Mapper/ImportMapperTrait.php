<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

trait ImportMapperTrait {
	/**
	 * @var ImportMapper
	 */
	protected $importMapper;

	/**
	 * @Inject
	 *
	 * @param ImportMapper $importMapper
	 *
	 * @return void
	 */
	public function setImportMapper(ImportMapper $importMapper): void {
		$this->importMapper = $importMapper;
	}
}
