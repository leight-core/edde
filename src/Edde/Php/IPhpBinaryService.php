<?php
declare(strict_types=1);

namespace Edde\Php;

interface IPhpBinaryService {
	/**
	 * Find the PHP executable file (a cli one).
	 *
	 * @param string|null $fallback
	 *
	 * @return string
	 */
	public function find(?string $fallback = null): string;
}
