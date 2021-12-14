<?php
declare(strict_types=1);

namespace Edde\Stream;

use Edde\Stream\Exception\InvalidStreamResourceException;
use function get_resource_type;
use function gettype;
use function is_resource;

class ResourceStream extends AbstractStream {
	static public function wrap($resource) {
		if (!is_resource($resource) || get_resource_type($resource) !== 'stream') {
			throw new InvalidStreamResourceException(sprintf('Wrong resource type [%s] or resource is not... resource [%s].', get_resource_type($resource), gettype($resource)));
		}
		return new self($resource);
	}
}
