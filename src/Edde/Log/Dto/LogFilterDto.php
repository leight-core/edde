<?php
declare(strict_types=1);

namespace Edde\Log\Dto;

use Edde\Repository\Dto\AbstractFilterDto;
use Edde\Repository\Dto\RangeDto;

class LogFilterDto extends AbstractFilterDto {
	/**
	 * @var string[]|void
	 */
	public $types;
	/**
	 * @var string[]|void
	 */
	public $userIds;
	/**
	 * @var string[]|void
	 */
	public $tags;
	/**
	 * @var RangeDto|void
	 */
	public $stamp;
	/**
	 * @var string|void
	 */
	public $reference;
}
