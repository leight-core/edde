<?php
declare(strict_types=1);

namespace Edde\Uuid;

use Nette\Utils\Random;
use Ramsey\Uuid\Uuid;

/**
 * Just a proxy service for internal implementation of UUID and other hash stuff.
 */
class UuidService {
	public function uuid4(): string {
		return Uuid::uuid4()->toString();
	}

	/**
	 * Magic method used to compute (almost) human rememberable hash; be careful, this
	 * method is not intended to be cryptographically secure.
	 *
	 * @param int    $length
	 * @param string $chars
	 *
	 * @return string
	 */
	public function humanHash(int $length = 10, string $chars = '0-9a-zA-Z'): string {
		return Random::generate($length, $chars);
	}
}
