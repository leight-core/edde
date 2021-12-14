<?php
declare(strict_types=1);

namespace Edde\Dto;

use Nette\SmartObject;
use Nette\Utils\Arrays;

abstract class AbstractDto implements IDto {
	use SmartObject;

	/**
	 * Create an instance of "this" DTO; does no checking, thus proper filling is up to the caller.
	 *
	 * One use case is with well known values (thus validity is "ensured"), another is with external reflection
	 * used on "this" class to see if everything is valid.
	 *
	 * This can keep whole DTO stuff extremely simple. Nice. And Shiny *!
	 *
	 * @param array $source
	 *
	 * @return static
	 */
	static public function create(array $source): self {
		return Arrays::toObject($source, new static);
	}
}
