<?php
declare(strict_types=1);

namespace Edde\Log\Dto\Create;

use Edde\Dto\AbstractDto;

class CreateDto extends AbstractDto {
	/** @var string */
	public $type;
	/** @var string */
	public $log;
	/** @var string */
	public $traceId;
	/** @var string */
	public $trace;
	/** @var string|null */
	public $referenceId;
	/** @var string|null */
	public $stack;
	/** @var string|null */
	public $userId;
	/** @var array|null */
	public $context;
	/**
	 * @var string[]
	 */
	public $tags = [];
}
