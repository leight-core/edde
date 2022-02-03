<?php
declare(strict_types=1);

namespace Edde\Plot;

use Edde\Plot\Dto\PlotDto;
use Edde\Query\Dto\Query;

interface IPlotService {
	/**
	 * Generate a plot by the given query.
	 *
	 * @param Query $query
	 *
	 * @return PlotDto
	 */
	public function plot(Query $query): PlotDto;
}
