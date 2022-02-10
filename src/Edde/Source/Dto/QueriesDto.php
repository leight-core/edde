<?php
declare(strict_types=1);

namespace Edde\Source\Dto;

use Edde\Dto\AbstractDto;

class QueriesDto extends AbstractDto {
	/**
	 * @var QueryDto[]
	 */
	public $queries = [];
}
