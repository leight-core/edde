<?php
declare(strict_types=1);

namespace Edde\Math;

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
}
