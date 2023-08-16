<?php
declare(strict_types=1);

namespace Edde\Sdk;

use DI\DependencyException;
use DI\NotFoundException;
use Edde\Rpc\Exception\RpcException;
use Edde\Schema\SchemaException;

interface IGenerator {
	/**
	 * Generates a file (or generates whatever) and returns file/directory/null.
	 *
	 * @return void
	 * @throws DependencyException
	 * @throws SchemaException
	 * @throws RpcException
	 * @throws NotFoundException
	 */
	public function generate(): void;
}
