<?php
declare(strict_types=1);

namespace Edde\Mapper;

use DI\Annotation\Inject;

trait MapperServiceTrait {
	/**
	 * @var MapperService
	 */
	protected $mapperService;

	/**
	 * @Inject
	 *
	 * @param MapperService $mapperService
	 *
	 * @return void
	 */
	public function setMapperService(MapperService $mapperService): void {
		$this->mapperService = $mapperService;
	}
}
