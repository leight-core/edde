<?php
declare(strict_types=1);

namespace Edde\Import\Template\Mapper;

trait ImportTemplateMapperTrait {
	/** @var ImportTemplateMapper */
	protected $importTemplateMapper;

	/**
	 * @Inject
	 *
	 * @param ImportTemplateMapper $importTemplateMapper
	 */
	public function setImportTemplateMapper(ImportTemplateMapper $importTemplateMapper): void {
		$this->importTemplateMapper = $importTemplateMapper;
	}
}
