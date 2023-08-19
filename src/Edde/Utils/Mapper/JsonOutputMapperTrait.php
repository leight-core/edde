<?php
declare(strict_types=1);

namespace Edde\Utils\Mapper;

use DI\Annotation\Inject;

trait JsonOutputMapperTrait {
	/**
	 * @var JsonOutputMapper
	 */
	protected $jsonOutputMapper;

	/**
	 * @Inject
	 *
	 * @param JsonOutputMapper $jsonOutputMapper
	 *
	 * @return void
	 */
	public function setJsonOutputMapper(JsonOutputMapper $jsonOutputMapper): void {
		$this->jsonOutputMapper = $jsonOutputMapper;
	}
}
