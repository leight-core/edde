<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

trait QueryGeneratorTrait {
	/** @var QueryGenerator */
	protected $queryGenerator;

	/**
	 * @Inject
	 *
	 * @param QueryGenerator $queryGenerator
	 */
	public function setQueryGenerator(QueryGenerator $queryGenerator): void {
		$this->queryGenerator = $queryGenerator;
	}
}
