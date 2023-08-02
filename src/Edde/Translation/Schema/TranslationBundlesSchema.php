<?php
declare(strict_types=1);

namespace Edde\Translation\Schema;

abstract class TranslationBundlesSchema {
	abstract public function bundles($array = true, $load = true): TranslationBundleSchema;
}
