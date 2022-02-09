<?php
declare(strict_types=1);

namespace Edde\Source;

use Edde\Source\Dto\SourceQueryDto;
use Generator;

interface ISource {
	/**
	 * @param string $query this is a special string used to request a data from the source
	 *
	 * @return Generator
	 */
	public function query(string $query): Generator;

	public function parse(string $query): SourceQueryDto;
}
