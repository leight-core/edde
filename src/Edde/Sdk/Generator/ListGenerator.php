<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Rest\Reflection\FetchEndpoint;
use Edde\Rest\Reflection\ListEndpoint;

class ListGenerator {
	use FetchGeneratorTrait;

	public function generate(ListEndpoint $endpoint): ?string {
		$export = $this->fetchGenerator->generate(FetchEndpoint::create([
			'class'    => $endpoint->class,
			'method'   => $endpoint->method,
			'link'     => $endpoint->link,
			'query'    => $endpoint->query,
			'roles'    => $endpoint->roles,
			'response' => $endpoint->item,
		]));
		return $export;
	}
}
