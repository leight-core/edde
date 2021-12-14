<?php
declare(strict_types=1);

namespace Edde\Repository\Dto;

use Edde\Dto\AbstractDto;

abstract class AbstractFilterDto extends AbstractDto {
	/**
	 * @var string|void
	 * @description search by id of an object
	 */
	public $id;
	/**
	 * @var string|void
	 * @description fulltext search over all supported fields on an object
	 */
	public $fulltext;
}
