<?php
declare(strict_types=1);

namespace Edde\Math;

interface IPasswordService {
	/**
	 * Tells if the given password and hash matches.
	 *
	 * @param string $password
	 * @param string $hash
	 *
	 * @return bool
	 */
	public function isMatch(string $password, string $hash): bool;

	/**
	 * Hashes a password.
	 *
	 * @param string $password
	 *
	 * @return bool
	 */
	public function hash(string $password): bool;
}
