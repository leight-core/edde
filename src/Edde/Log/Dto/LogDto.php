<?php
declare(strict_types=1);

namespace Edde\Log\Dto;

use Edde\Dto\AbstractDto;
use Edde\Tag\Dto\TagDto;
use Marsh\User\Dto\UserDto;

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
	 * @var string|null
	 */
	public $stack;
	/**
	 * @var string
	 */
	public $stamp;
	/**
	 * @var string|null
	 */
	public $trace;
	/**
	 * @var string|null
	 */
	public $reference;
	/**
	 * @var float
	 */
	public $microtime;
	/**
	 * @var UserDto|null
	 */
	public $user;
	/**
	 * @var TagDto[]
	 */
	public $tags;
}
