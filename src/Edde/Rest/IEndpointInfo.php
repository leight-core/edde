<?php
declare(strict_types=1);

namespace Edde\Rest\;

interface IEndpointInfo {
	function getId(string $name): string;
}
