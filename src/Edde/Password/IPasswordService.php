<?php
declare(strict_types=1);

namespace Edde\Password;

use Edde\Password\Exception\PasswordException;

interface IPasswordService {
	/**
	 * Tells if the given password and hash matches.
	 *
	 * @param string      $password
	 * @param string|null $hash
	 *
	 * @return bool
	 */
	public function isMatch(string $password, ?string $hash): bool;

	/**
	 * Check a password; if there is no match, an exception is thrown.
	 *
	 * @param string $password
	 * @param string $hash
	 *
	 * @throws PasswordException
	 */
	public function check(string $password, string $hash): void;

	/**
	 * Hashes a password.
	 *
	 * @param string $password
	 * @param int    $cost
	 *
	 * @return string
	 */
	public function hash(string $password, int $cost = 12): string;
}
