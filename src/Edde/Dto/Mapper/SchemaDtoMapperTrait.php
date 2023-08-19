<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use DI\Annotation\Inject;

trait SchemaDtoMapperTrait {
	/**
	 * @var SchemaDtoMapper
	 */
	protected $schemaDtoMapper;

	/**
	 * @Inject
	 *
	 * @param SchemaDtoMapper $schemaDtoMapper
	 *
	 * @return void
	 */
	public function setSchemaDtoMapper(SchemaDtoMapper $schemaDtoMapper): void {
		$this->schemaDtoMapper = $schemaDtoMapper;
	}
}
