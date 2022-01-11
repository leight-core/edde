<?php
declare(strict_types=1);

namespace Edde\Job\Dto\Log;

use Edde\Dto\AbstractDto;

class JobLogDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var int
	 */
	public $level;
	/**
	 * @var string
	 */
	public $message;
	/**
	 * @var string|null
	 */
	public $type;
	/**
	 * @var string|null
	 */
	public $reference;
	/**
	 * @var mixed|null
	 */
	public $item;
}
