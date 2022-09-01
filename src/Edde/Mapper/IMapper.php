<?php
declare(strict_types=1);

namespace Edde\Mapper;

use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Generator;

interface IMapper {
	/**
	 * Map whole iterable to an output.
	 *
	 * @param iterable $source
	 * @param null     $params
	 *
	 * @return array
	 */
	public function map(iterable $source, $params = null): array;

	/**
	 * Stream through the mapper.
	 *
	 * @param iterable $source
	 * @param null     $params
	 *
	 * @return Generator
	 */
	public function stream(iterable $source, $params = null): Generator;

	/**
	 * Map one-to-one item.
	 *
	 * @param       $item
	 * @param null  $params
	 *
	 * @throws SkipException when an item should be silently skipped
	 * @throws ItemException when an item has some error
	 */
	public function item($item, $params = null);
}
