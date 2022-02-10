<?php
declare(strict_types=1);

namespace Edde\Log\Dto;

use Edde\Bridge\User\UserDto;
use Edde\Dto\AbstractDto;

class LogDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var string
	 */
	public $type;
	/**
	 * @var string
	 */
	public $log;
	/**
	 * @var string|void
	 */
	public $stack;
	/**
	 * @var string
	 */
	public $stamp;
	/**
	 * @var string|void
	 */
	public $trace;
	/**
	 * @var string|void
	 */
	public $reference;
	/**
	 * @var float
	 */
	public $microtime;
	/**
	 * @var UserDto|void
	 */
	public $user;
	/**
	 * @var string[]
	 */
	public $tags;
}
