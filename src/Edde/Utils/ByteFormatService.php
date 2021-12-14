<?php
declare(strict_types=1);

namespace Edde\Utils;

use function ByteUnits\bytes;

class ByteFormatService {
	/**
	 * @param $bytes
	 *
	 * @return string
	 */
	public function toBinary($bytes): string {
		return bytes($bytes)->asBinary()->format($bytes);
	}

	/**
	 * @param $bytes
	 *
	 * @return string
	 */
	public function toDecimal($bytes): string {
		return bytes($bytes)->asMetric()->format($bytes);
	}
}
