<?php
declare(strict_types=1);

namespace Edde\Math;

use Nette\Utils\Random;

class RandomService {
	/**
	 * @param float $probability
	 * @param int   $length
	 *
	 * @return bool
	 */
	public function isHit(float $probability, int $length = 10000): bool {
		return mt_rand(1, $length) <= ($probability * $length);
	}

	/**
	 * Generate (somehow) human rememberable code.
	 *
	 * @return string
	 */
	public function code(): string {
		return Random::generate(8);
	}

	public function chars(int $limit = 12): string {
		return Random::generate($limit, 'a-zA-Z');
	}
}
