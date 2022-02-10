<?php
declare(strict_types=1);

namespace Edde\Mapper;

use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;

interface IMapper {
	/**
	 * Map whole iterable to an output.
	 *
	 * @param iterable $source
	 *
	 * @return array
	 */
	public function map(iterable $source): array;

	/**
	 * Map one-to-one item.
	 *
	 * @param       $item
	 *
	 * @throws SkipException when an item should be silently skipped
	 * @throws ItemException when an item has some error
	 */
	public function item($item);
}
