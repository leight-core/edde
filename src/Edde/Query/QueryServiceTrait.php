<?php
declare(strict_types=1);

namespace Edde\Query;

use DI\Annotation\Inject;

trait QueryServiceTrait {
	/** @var QueryService */
	protected $queryService;

	/**
	 * @Inject
	 *
	 * @param QueryService $queryService
	 */
	public function setQueryService(QueryService $queryService): void {
		$this->queryService = $queryService;
	}
}
