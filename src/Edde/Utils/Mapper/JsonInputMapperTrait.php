<?php
declare(strict_types=1);

namespace Edde\Utils\Mapper;

use DI\Annotation\Inject;

trait JsonInputMapperTrait {
	/**
	 * @var JsonInputMapper
	 */
	protected $jsonInputMapper;

	/**
	 * @Inject
	 *
	 * @param JsonInputMapper $jsonInputMapper
	 *
	 * @return void
	 */
	public function setJsonInputMapper(JsonInputMapper $jsonInputMapper): void {
		$this->jsonInputMapper = $jsonInputMapper;
	}
}
