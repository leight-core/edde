<?php
declare(strict_types=1);

namespace Edde\Dto\Common;

use Edde\Dto\AbstractDto;

class AddressDto extends AbstractDto {
	/**
	 * @var string|null
	 */
	public $street;
	/**
	 * @var string|null
	 */
	public $city;
	/**
	 * @var string|null
	 */
	public $zip;
}
