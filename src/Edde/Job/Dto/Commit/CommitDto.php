<?php
declare(strict_types=1);

namespace Edde\Job\Dto\Commit;

use Edde\Dto\AbstractDto;

class CommitDto extends AbstractDto {
	/** @var string|void */
	public $jobId;
	/** @var string|void */
	public $userId;
}
