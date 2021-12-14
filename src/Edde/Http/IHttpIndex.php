<?php
declare(strict_types=1);

namespace Edde\Http;

use Edde\Rest\Reflection\Endpoint;

interface IHttpIndex {
	public function register(string $endpoint): void;

	/** @return Endpoint[] */
	public function endpoints(callable $onRebuild = null): array;

	public function endpoint(string $name): Endpoint;
}
