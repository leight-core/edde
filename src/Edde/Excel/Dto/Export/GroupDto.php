<?php
declare(strict_types=1);

namespace Edde\Excel\Dto\Export;

use Edde\Dto\AbstractDto;

class GroupDto extends AbstractDto {
	/**
	 * @var array
	 */
	public $first;
	/**
	 * @var string[]
	 * @description queries sent directly to SourceService
	 */
	public $queries = [];
	/**
	 * @var CellDto[]
	 * @description cells with export meta data
	 */
	public $cells = [];
}
