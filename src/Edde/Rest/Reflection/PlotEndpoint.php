<?php
declare(strict_types=1);

namespace Edde\Rest\Reflection;

use Edde\Reflection\Dto\Type\AbstractType;

class PlotEndpoint extends Endpoint {
	/** @var AbstractType */
	public $response;
}
