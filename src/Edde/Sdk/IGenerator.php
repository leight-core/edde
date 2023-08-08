<?php
declare(strict_types=1);

namespace Edde\Sdk;

interface IGenerator {
	/**
	 * Generates a file (or generates whatever) and returns file/directory/null.
	 *
	 * @return string|null
	 */
	public function generate(): void;
}
