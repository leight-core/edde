<?php
declare(strict_types=1);

namespace Edde\Query\Dto;

use Edde\Dto\AbstractDto;
use Edde\Query\Exception\InvalidLimitException;
use Edde\Query\Exception\InvalidQueryException;
use stdClass;

/**
 * @template TOrderBy=void
 * @template TFilter=void
 */
class Query extends AbstractDto {
	/**
	 * @var int|null
	 * @description currently requested page
	 */
	public $page = 0;
	/**
	 * @var int|null
	 * @description limit number of items per page
	 */
	public $size = 1;
	/**
	 * @var TOrderBy|null|void
	 */
	public $orderBy;
	/**
	 * @var TFilter|null|void
	 */
	public $filter;

	public function offset() {
		return $this->page * $this->size;
	}

	/**
	 * @param $total
	 *
	 * @return $this
	 *
	 * @throws InvalidLimitException
	 * @throws InvalidQueryException
	 */
	public function validate($total): Query {
		if ($this->page < 0) {
			throw new InvalidQueryException("Page must be a positive number");
		}
		if ($this->size < 1) {
			throw new InvalidLimitException("Size must be a positive number and higher than 0");
		}
		if ($this->size > 100) {
			throw new InvalidLimitException("Size cannot be higher than 100");
		}
		$pages = floor($total / $this->size);
		if ($this->page > $pages) {
			throw new InvalidQueryException("Out of range: page [{$this->page}] cannot be higher than [{$pages}]");
		}
		return $this;
	}

	public function withFilter(array $filter): Query {
		$this->filter = $this->filter ?? new stdClass();
		foreach ($filter as $k => $v) {
			$this->filter->$k = $v;
		}
		return $this;
	}
}
