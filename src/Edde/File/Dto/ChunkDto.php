<?php
declare(strict_types=1);

namespace Edde\File\Dto;

use Edde\Dto\AbstractDto;
use Edde\Stream\IStream;

class ChunkDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $hash;
	/**
	 * @var IStream
	 * @internal
	 */
	public $stream;
}
