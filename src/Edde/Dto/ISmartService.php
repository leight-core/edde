<?php
declare(strict_types=1);

namespace Edde\Dto;

interface ISmartService {
	/**
	 * Creates smart dto from the given object using provided shape; the shape
	 * controls properties and other stuff an $object must provide/fulfill.
	 *
	 * @param object $object
	 * @param string $shape
	 *
	 * @return SmartDto
	 */
	public function from(object $object, string $shape): SmartDto;
}
