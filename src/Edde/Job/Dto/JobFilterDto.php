<?php
declare(strict_types=1);

namespace Edde\Job\Dto;

use Edde\Repository\Dto\AbstractFilterDto;

class JobFilterDto extends AbstractFilterDto {
	/** @var string|void */
	public $userId;
	/** @var string[]|void */
	public $services;
	/** @var string|void */
	public $params;
	/** @var int[]|void */
	public $status;
	/** @var bool|void */
	public $commit;
}
