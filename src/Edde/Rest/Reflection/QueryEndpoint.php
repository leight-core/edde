<?php
declare(strict_types=1);

namespace Edde\Rest\Reflection;

use Edde\Reflection\Dto\Type\AbstractType;

class QueryEndpoint extends Endpoint {
	/** @var AbstractType */
	public $item;
	/** @var AbstractType */
	public $orderBy;
	/** @var AbstractType */
	public $filter;
}
