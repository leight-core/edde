<?php
declare(strict_types=1);

namespace Edde\Excel\Dto\Export;

use Edde\Dto\AbstractDto;
use Edde\Source\Dto\SourcesDto;

class TabDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var SourcesDto
	 */
	public $sources;
	/**
	 * @var GroupsDto
	 */
	public $groups;
}
