<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type;

use Edde\Dto\AbstractDto;
use Edde\Reflection\Dto\Type\Utils\TypeTrait;

abstract class AbstractType extends AbstractDto {
	use TypeTrait;
}
