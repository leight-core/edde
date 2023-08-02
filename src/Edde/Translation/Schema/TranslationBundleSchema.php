<?php
declare(strict_types=1);

namespace Edde\Translation\Schema;

abstract class TranslationBundleSchema {
	abstract function language(): string;

	abstract function translations($array = true, $load = true): TranslationSchema;
}
