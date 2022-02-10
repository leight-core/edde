<?php
declare(strict_types=1);

namespace Edde\Source;

use Edde\Source\Dto\QueriesDto;
use Edde\Source\Dto\SourcesDto;

interface ISourceService {
	/**
	 * Prepare source for the given parameters
	 *
	 * @param SourcesDto $sources sources available in the result
	 * @param QueriesDto $queries queries applied on the sources
	 *
	 * @return ISource result connected and filtered
	 */
	public function source(Dto\SourcesDto $sources, QueriesDto $queries): ISource;
}
