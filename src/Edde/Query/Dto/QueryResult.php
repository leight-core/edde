<?php
declare(strict_types=1);

namespace Edde\Query\Dto;

use Edde\Dto\AbstractDto;

/**
 * @template TItem
 */
class QueryResult extends AbstractDto {
	/**
	 * @var int
	 * @description number of total available items in the source
	 */
	public $total;
	/**
	 * @var int
	 * @description current page size
	 */
	public $size;
	/**
	 * @var int
	 * @description total available pages (precomputed based on total number of items and page size)
	 */
	public $pages;
	/**
	 * @var int
	 * @description number of items on the current page; usually same as page size, could be less
	 */
	public $count;
	/**
	 * @var TItem[]
	 * @description items on the page
	 */
	public $items;

	/**
	 * @param int     $total
	 * @param int     $size
	 * @param TItem[] $items
	 */
	public function __construct(int $total, int $size, array $items) {
		$this->total = $total;
		$this->size = $size;
		$this->pages = ceil($total / max($size, 1));
		$this->count = count($items);
		$this->items = $items;
	}
}
