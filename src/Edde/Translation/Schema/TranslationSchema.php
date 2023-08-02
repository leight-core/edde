<?php
declare(strict_types=1);

namespace Edde\Translation\Schema;

abstract class TranslationSchema {
	abstract function key(): string;

	abstract function value(): string;
}
