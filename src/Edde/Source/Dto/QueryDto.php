<?php
declare(strict_types=1);

namespace Edde\Source\Dto;

use Edde\Dto\AbstractDto;
use Edde\Query\Dto\Query;

class QueryDto extends AbstractDto {
	/**
	 * @var string
	 * @description name of the source this query belongs to
	 */
	public $name;
	/**
	 * @var Query|null|void
	 * @description the query itself
	 */
	public $query;
}
