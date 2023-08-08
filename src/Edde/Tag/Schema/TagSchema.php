<?php
declare(strict_types=1);

namespace Edde\Tag\Schema;

abstract class TagSchema {
	abstract function id(): string;

	abstract function code(): string;

	abstract function tag(): string;

	abstract function group(): string;

	abstract function sort(): ?int;
}
