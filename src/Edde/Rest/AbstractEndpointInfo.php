<?php
declare(strict_types=1);

namespace Edde\Rest;

abstract class AbstractEndpointInfo implements IEndpointInfo {
	function getId(string $name): string {
		return implode('.', array_filter(explode(".", str_replace([
			"\\",
			"Api",
			"Endpoint",
		], [
			".",
			"",
		], $name))));
	}
}
