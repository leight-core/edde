<?php
declare(strict_types=1);

namespace Edde\Mapper;

trait MapperServiceTrait {
	/**
	 * @var IMapperService
	 */
	protected $mapperService;

	/**
	 * @Inject
	 *
	 * @param IMapperService $mapperService
	 *
	 * @return void
	 */
	public function setMapperService(IMapperService $mapperService): void {
		$this->mapperService = $mapperService;
	}
}
