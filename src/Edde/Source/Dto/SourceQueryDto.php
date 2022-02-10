<?php
declare(strict_types=1);

namespace Edde\Source\Dto;

use Edde\Dto\AbstractDto;

class SourceQueryDto extends AbstractDto {
	/**
	 * @var string
	 * @description type of a query (value/iterator/literal/whatever...)
	 */
	public $type;
	/**
	 * @var string|null
	 * @description if this query requires source, it's here; source should ensure it's repository exists
	 */
	public $source;
	/**
	 * @var string[]|string|null
	 * @description an array of path or a single string as scalar (literal) value
	 */
	public $value;
}
